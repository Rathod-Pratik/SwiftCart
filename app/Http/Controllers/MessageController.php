<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\AdminQueryReceivedNotification;
use App\Notifications\NewCustomerQueryReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    /**
     * List messages inside a conversation (paginated, oldest first).
     */
    public function index(Request $request, Conversation $conversation)
    {
        try {
            Gate::authorize('view', $conversation);

            $messages = $conversation->messages()
                ->with('sender')
                ->orderBy('created_at')
                ->paginate(50);

            // Mark other participant's unread messages as read when viewed
            $conversation->messages()
                ->where('sender_id', '!=', $request->user()->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'data' => $messages,
                'current_user_id' => $request->user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching messages', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching messages',
            ], 500);
        }
    }

    /**
     * Send a message in a conversation (text and/or attachment).
     */
    public function store(Request $request, Conversation $conversation)
    {
        try {
            Gate::authorize('sendMessage', $conversation);

            $validated = $request->validate([
                'body' => 'required_without:attachment|nullable|string|max:2000',
                'attachment' => 'required_without:body|nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
            ], [
                'body.required_without' => 'A message body or attachment is required.',
            ]);

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
            }

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'body' => $validated['body'] ?? null,
                'attachment_path' => $attachmentPath,
            ]);

            $conversation->update(['last_message_at' => now()]);

            try {
                $conversation->loadMissing(['customer', 'vendor', 'admin']);
                $sender = $request->user();

                if ($conversation->vendor && $sender->id !== (int) $conversation->vendor_id) {
                    $conversation->vendor->notify(new NewCustomerQueryReceived($conversation, $message, $sender));
                } elseif ($conversation->customer && $sender->id !== (int) $conversation->customer_id) {
                    $conversation->customer->notify(new NewCustomerQueryReceived($conversation, $message, $sender));
                }

                if ($conversation->type === 'customer_admin' || $conversation->admin_id) {
                    if (! $sender->isAdmin()) {
                        $admins = User::where('role', 'admin')->get();
                        if ($admins->isNotEmpty()) {
                            Notification::send($admins, new AdminQueryReceivedNotification($conversation, $message, $sender));
                        }
                    }
                }
            } catch (\Throwable $notifError) {
                Log::warning('Failed sending chat notification: '.$notifError->getMessage());
            }

            broadcast(new MessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error sending message', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while sending message',
            ], 500);
        }
    }
}
