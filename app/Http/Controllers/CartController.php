<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Throwable;

class CartController extends Controller
{
    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ];

    protected array $messages = [
        'product_id.required' => 'The product_id field is required.',
        'product_id.exists' => 'The selected product_id is invalid.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Gate::authorize('viewAny', Cart::class);
            $validation = request()->validate([
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
            $page = $validation['page'] ?? 1;
            $perPage = $validation['per_page'] ?? 10;

            $cacheKey = "carts:user:{$userId}:page:{$page}:per_page:{$perPage}";

            $carts = Cache::tags(["carts:user:{$userId}"])
                ->remember($cacheKey, now()->addMinutes(10), function () use ($userId, $perPage) {
                    return Cart::where('user_id', $userId)->paginate($perPage);
                });

            if ($carts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No carts found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Carts fetched successfully',
                'data' => $carts,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching carts',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Cart::class);
            $validatedData = $request->validate($this->rules, $this->messages);

            $exists = Cart::where('user_id', $request->user()->id)
                ->where('product_id', $request->input('product_id'))
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item already in cart',
                ], 409);
            }

            $validatedData['user_id'] = $request->user()->id;
            $cart = Cart::create($validatedData);
            // No manual Cache::flush() needed — CartObserver::created() handles it

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => $cart,
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
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while adding item to cart',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        try {
            Gate::authorize('update', $cart);
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
            ], [
                'quantity.required' => 'The quantity field is required.',
                'quantity.integer' => 'The quantity must be an integer.',
                'quantity.min' => 'The quantity must be at least 1.',
            ]);

            $cart->update($validatedData);
            // No manual Cache::flush() needed — CartObserver::updated() handles it

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'data' => $cart,
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
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while updating cart item',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            Gate::authorize('delete', $cart);
            $cart->delete();
            // No manual Cache::flush() needed — CartObserver::deleted() handles it

            return response()->json([
                'success' => true,
                'message' => 'Cart item removed successfully',
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while removing cart item',
            ], 500);
        }
    }
}
