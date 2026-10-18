<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ConversationController extends Controller
{
    /**
     * List all conversations for the authenticated user,
     * regardless of whether they're the customer, vendor, or admin side.
     */
    public function index(Request $request)
    {
        try {
            Gate::authorize('viewAny', Conversation::class);
            $userId = $request->user()->id;

            $conversations = Conversation::where('customer_id', $userId)
                ->orWhere('vendor_id', $userId)
                ->orWhere('admin_id', $userId)
                ->with(['latestMessage', 'customer', 'vendor', 'admin', 'product'])
                ->orderByDesc('last_message_at')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $conversations,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching conversations', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching conversations',
            ], 500);
        }
    }

    /**
     * List customer conversations assigned to the authenticated vendor.
     */
    public function vendorIndex(Request $request)
    {
        $vendorId = (int) $request->user()->id;
        $search = trim((string) $request->query('search', ''));

        $conversations = Conversation::query()
            ->where('type', 'customer_vendor')
            ->where('vendor_id', $vendorId)
            ->with([
                'customer:id,name,email,phone',
                'product:id,name',
                'latestMessage.sender:id,name',
            ])
            ->withCount([
                'messages as unread_messages_count' => fn ($query) => $query
                    ->whereNull('read_at')
                    ->where('sender_id', '!=', $vendorId),
            ])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->whereHas('customer', function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('product', function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%");
                    })->orWhereHas('latestMessage', function ($query) use ($search): void {
                        $query->where('body', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->paginate((int) $request->query('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $conversations,
        ]);
    }

    /**
     * Show one conversation only when it belongs to the authenticated vendor.
     */
    public function vendorShow(Request $request, Conversation $conversation)
    {
        abort_unless(
            $conversation->type === 'customer_vendor'
                && (int) $conversation->vendor_id === (int) $request->user()->id,
            404
        );

        $conversation->load([
            'customer:id,name,email,phone,address',
            'vendor:id,name,email',
            'product:id,name,price',
            'order:id,order_number,order_status',
            'latestMessage.sender:id,name',
        ]);

        return response()->json([
            'success' => true,
            'data' => $conversation,
        ]);
    }

    /**
     * Start (or resume) a conversation. If one already exists between
     * these two parties for this product/order, reuse it instead of
     * creating duplicates.
     */
    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Conversation::class);
            $validated = $request->validate([
                'type' => 'required|in:customer_vendor,customer_admin',
                'product_id' => 'required_if:type,customer_vendor|nullable|exists:products,id',
                'order_id' => 'nullable|exists:orders,id',
            ], [
                'type.required' => 'The conversation type is required.',
                'product_id.required_if' => 'A product must be specified to chat with a vendor.',
            ]);

            $customerId = $request->user()->id;
            $vendorId = null;
            $adminId = null;

            if ($validated['type'] === 'customer_vendor') {
                // Derive vendor from the product — never trust a client-sent vendor_id directly
                $product = Product::findOrFail($validated['product_id']);
                $vendorId = $product->vendor_id;

                if ($vendorId === $customerId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot start a chat with yourself',
                    ], 422);
                }
            } else {
                // customer_admin — assign to an available admin (or a support queue)
                $adminId = User::query()->where('role', 'admin')->value('id');
            }

            $query = Conversation::where('customer_id', $customerId)->where('type', $validated['type']);

            if ($vendorId) {
                $query->where('vendor_id', $vendorId);
            }
            if (! empty($validated['product_id'])) {
                $query->where('product_id', $validated['product_id']);
            }

            $conversation = $query->first() ?? Conversation::create([
                'type' => $validated['type'],
                'customer_id' => $customerId,
                'vendor_id' => $vendorId,
                'admin_id' => $adminId,
                'product_id' => $validated['product_id'] ?? null,
                'order_id' => $validated['order_id'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Conversation ready',
                'data' => $conversation->load(['vendor', 'admin', 'product']),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error starting conversation', ['exception' => $e]);

            return response()->json(['success' => false, 'message' => 'Error occurred while starting conversation'], 500);
        }
    }
}
