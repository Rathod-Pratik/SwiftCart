<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get paginated notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $query = $user->notifications();

            if ($request->has('unread_only') && $request->boolean('unread_only')) {
                $query = $user->unreadNotifications();
            }

            if ($request->filled('type')) {
                $type = $request->query('type');
                $query->where('type', 'like', "%{$type}%");
            }

            $perPage = (int) $request->query('per_page', 15);
            $notifications = $query->latest()->paginate($perPage);

            $unreadCount = $user->unreadNotifications()->count();

            $formatted = $notifications->through(function (DatabaseNotification $notif): array {
                $data = $notif->data ?? [];
                $rawType = class_basename($notif->type);
                $title = $data['title'] ?? 'Notification';
                $description = $data['description'] ?? $data['message'] ?? ($data['reason'] ?? '');

                if (empty($description) && isset($data['product_name']) && ! empty($data['product_name'])) {
                    $description = 'Product "'.$data['product_name'].'": '.($data['reason'] ?? 'Notice');
                }

                $category = $data['category'] ?? null;
                if (! $category) {
                    $typeLower = strtolower($rawType);
                    if (str_contains($typeLower, 'product')) {
                        $category = 'product';
                    } elseif (str_contains($typeLower, 'order')) {
                        $category = 'order';
                    } elseif (str_contains($typeLower, 'query') || str_contains($typeLower, 'message')) {
                        $category = 'query';
                    } elseif (str_contains($typeLower, 'service')) {
                        $category = 'service';
                    } elseif (str_contains($typeLower, 'coupon')) {
                        $category = 'coupon';
                    } elseif (str_contains($typeLower, 'category')) {
                        $category = 'inventory';
                    } elseif (str_contains($typeLower, 'vendor')) {
                        $category = 'vendor';
                    } else {
                        $category = 'system';
                    }
                }

                $priority = $data['priority'] ?? (str_contains(strtolower($rawType), 'removed') ? 'high' : 'normal');

                $isAdmin = $user->isAdmin();
                $actionUrl = $data['action_url'] ?? match ($category) {
                    'order' => $isAdmin ? route('admin.dashboard') : route('vendor.order'),
                    'product', 'inventory' => $isAdmin ? route('admin.product') : route('vendor.product'),
                    'query' => $isAdmin ? route('admin.query') : route('vendor.query'),
                    'service' => $isAdmin ? route('admin.dashboard') : route('vendor.service'),
                    'coupon' => $isAdmin ? route('admin.dashboard') : route('vendor.coupon'),
                    'vendor' => $isAdmin ? route('admin.vendor') : route('vendor.dashboard'),
                    default => null,
                };

                return [
                    'id' => (string) $notif->id,
                    'type' => $rawType,
                    'category' => $category,
                    'priority' => $priority,
                    'title' => $title,
                    'description' => $description,
                    'data' => $data,
                    'read' => ! is_null($notif->read_at),
                    'read_at' => $notif->read_at?->toISOString(),
                    'created_at' => $notif->created_at?->toISOString(),
                    'time_ago' => $notif->created_at?->diffForHumans() ?? 'Just now',
                    'action_url' => $actionUrl,
                    'action_label' => $data['action_label'] ?? 'View',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'unread_count' => $unreadCount,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Unable to read database notifications: '.$e->getMessage());

            return response()->json([
                'success' => true,
                'data' => [
                    'data' => [],
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1,
                ],
                'unread_count' => 0,
                'fallback' => true,
            ]);
        }
    }

    /**
     * Get the total unread notification count.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Unable to fetch unread notification count: '.$e->getMessage());

            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $notification = $user->notifications()->where('id', $id)->first();

            if (! $notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error marking notification as read: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not update notification status',
            ], 500);
        }
    }

    /**
     * Mark a single notification as unread.
     */
    public function markAsUnread(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $notification = $user->notifications()->where('id', $id)->first();

            if (! $notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->update(['read_at' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread',
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error marking notification as unread: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not update notification status',
            ], 500);
        }
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error marking all notifications as read: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not mark notifications as read',
            ], 500);
        }
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $notification = $user->notifications()->where('id', $id)->first();

            if (! $notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted',
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error deleting notification: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not delete notification',
            ], 500);
        }
    }
}

