<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected array $rules = [
        'discount' => 'nullable|numeric|min:0',
        'discount_code' => 'nullable|string|max:255',
        'shipping_cost' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'payment_method' => 'required|string|max:255',
        'transaction_id' => 'nullable|string|max:255',
        'shipping_name' => 'required|string|max:255',
        'shipping_phone' => 'required|string|max:255',
        'shipping_address' => 'required|string',
        'shipping_city' => 'required|string|max:255',
        'shipping_state' => 'required|string|max:255',
        'shipping_postal_code' => 'required|string|max:255',
        'shipping_country' => 'required|string|max:255',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.product_name' => 'required|string|max:255',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.total_price' => 'required|numeric|min:0',
    ];

    protected array $messages = [
        'tax.min' => 'The tax must be at least 0.',
        'transaction_id.string' => 'The transaction ID must be a string.',
        'transaction_id.max' => 'The transaction ID must not exceed 255 characters.',
        'shipping_name.required' => 'The shipping name field is required.',
        'shipping_name.string' => 'The shipping name must be a string.',
        'shipping_name.max' => 'The shipping name must not exceed 255 characters.',
        'shipping_phone.required' => 'The shipping phone field is required.',
        'shipping_phone.string' => 'The shipping phone must be a string.',
        'shipping_phone.max' => 'The shipping phone must not exceed 255 characters.',
        'shipping_address.required' => 'The shipping address field is required.',
        'shipping_address.string' => 'The shipping address must be a string.',
        'shipping_city.required' => 'The shipping city field is required.',
        'shipping_city.string' => 'The shipping city must be a string.',
        'shipping_city.max' => 'The shipping city must not exceed 255 characters.',
        'shipping_state.required' => 'The shipping state field is required.',
        'shipping_state.string' => 'The shipping state must be a string.',
        'shipping_state.max' => 'The shipping state must not exceed 255 characters.',
        'shipping_postal_code.required' => 'The shipping postal code field is required.',
        'shipping_postal_code.string' => 'The shipping postal code must be a string.',
        'shipping_postal_code.max' => 'The shipping postal code must not exceed 255 characters.',
        'shipping_country.required' => 'The shipping country field is required.',
        'shipping_country.string' => 'The shipping country must be a string.',
        'shipping_country.max' => 'The shipping country must not exceed 255 characters.',
        'notes.string' => 'The notes must be a string.',
        'notes.max' => 'The notes must not exceed 255 characters.',
        'items.required' => 'The items field is required.',
        'items.array' => 'The items must be an array.',
        'items.min' => 'The items must contain at least one item.',
        'items.*.product_id.required' => 'The product ID field is required for each item.',
        'items.*.product_id.exists' => 'The specified product does not exist for each item.',
        'items.*.product_name.required' => 'The product name field is required for each item.',
        'items.*.product_name.string' => 'The product name must be a string for each item.',
        'items.*.product_name.max' => 'The product name must not exceed 255 characters for each item.',
        'items.*.quantity.required' => 'The quantity field is required for each item.',
        'items.*.quantity.integer' => 'The quantity must be an integer for each item.',
        'items.*.quantity.min' => 'The quantity must be at least 1 for each item.',
        'items.*.price.required' => 'The price field is required for each item.',
        'items.*.price.numeric' => 'The price must be a number for each item.',
        'items.*.price.min' => 'The price must be at least 0 for each item.',
        'items.*.total_price.required' => 'The total price field is required for each item.',
        'items.*.total_price.numeric' => 'The total price must be a number for each item.',
        'items.*.total_price.min' => 'The total price must be at least 0 for each item.',
    ];

    public function index()
    {
        try {
            Gate::authorize('viewAny', Order::class);
            $validationData = request()->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
            ], [
                'page.integer' => 'The page must be an integer.',
                'page.min' => 'The page must be at least 1.',
                'per_page.integer' => 'The per_page must be an integer.',
                'per_page.min' => 'The per_page must be at least 1.',
                'per_page.max' => 'The per_page may not be greater than 100.',
            ]);

            $userId = auth()->id();
            $page = $validationData['page'] ?? 1;
            $perPage = $validationData['per_page'] ?? 10;

            $cacheKey = "orders:user:{$userId}:page:{$page}:per_page:{$perPage}";

            $orders = Cache::tags(["orders:user:{$userId}"])
                ->remember($cacheKey, now()->addMinutes(10), function () use ($userId, $perPage) {
                    return Order::where('user_id', $userId)->paginate($perPage);
                });

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No orders found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orders fetched successfully',
                'data' => $orders,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while fetching orders', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching orders',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Order::class);
            $validated = $request->validate($this->rules, $this->messages);

            Log::info('Order validation passed', ['validated' => $validated]);

            $order = DB::transaction(function () use ($validated, $request) {

                $subtotal = 0;
                $itemsData = [];

                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $lineTotal = $product->price * $item['quantity'];
                    $subtotal += $lineTotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'total_price' => $lineTotal,
                    ];
                }

                $discount = $validated['discount'] ?? 0;
                $shippingCost = $validated['shipping_cost'] ?? 0;
                $tax = $validated['tax'] ?? 0;

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'order_number' => 'ORD-'.strtoupper(uniqid()),
                    'subtotal' => $subtotal,
                    'discount' => $discount ?? 0,
                    'discount_code' => $validated['discount_code'] ?? null,
                    'discount_id' => $validated['discount_id'] ?? null,
                    'shipping_cost' => $shippingCost,
                    'tax' => $tax,
                    'total_amount' => $subtotal - $discount + $shippingCost + $tax,
                    'order_status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'pending',
                    'shipping_name' => $validated['shipping_name'],
                    'shipping_phone' => $validated['shipping_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'shipping_city' => $validated['shipping_city'],
                    'shipping_state' => $validated['shipping_state'],
                    'shipping_postal_code' => $validated['shipping_postal_code'],
                    'shipping_country' => $validated['shipping_country'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($itemsData as &$data) {
                    $data['order_id'] = $order->id;
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                }
                OrderItem::insert($itemsData);

                return $order->load('items');
            });

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while creating order', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while creating order',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            Gate::authorize('view', $order);

            return response()->json([
                'success' => true,
                'message' => 'Order fetched successfully',
                'data' => $order->load('items'),
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while fetching order', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching order',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            Gate::authorize('delete', $order);

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while deleting order', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting order',
            ], 500);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:pending,processing,completed,cancelled',
            ], [
                'status.required' => 'The status field is required.',
                'status.string' => 'The status must be a string.',
                'status.in' => 'The status must be one of the following: pending, processing, completed, cancelled.',
            ]);

            Gate::authorize('update', $order);
            $order->update(['order_status' => $validated['status']]);

            if ($validated['status'] === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'Order marked as completed successfully',
                    'data' => $order->load('items'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => $order->load('items'),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while updating order status', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while updating order status',
            ], 500);
        }
    }
}
