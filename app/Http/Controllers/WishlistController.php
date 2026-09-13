<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class WishlistController extends Controller
{
    private array $rules = [
        'product_id' => 'required|exists:products,id',
    ];

    private array $messages = [
        'product_id.required' => 'The product_id field is required.',
        'product_id.exists' => 'The selected product_id is invalid.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Gate::authorize('viewAny', Wishlist::class);

            $validatedData = request()->validate([
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
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

            $cacheKey = "wishlists:user:{$userId}:page:{$page}:per_page:{$perPage}";

            $wishlists = Cache::tags(["wishlists:user:{$userId}"])
                ->remember($cacheKey, now()->addMinutes(10), function () use ($userId, $perPage) {
                    return Wishlist::where('user_id', $userId)->paginate($perPage);
                });

            if ($wishlists->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No wishlists found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Wishlists fetched successfully',
                'data' => $wishlists,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view wishlists',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching wishlists',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Wishlist::class);

            $validatedData = $request->validate($this->rules, $this->messages);
            $validatedData['user_id'] = Auth::id();
            $validatedData['product_id'] = $request->input('product_id');
            $exists = Wishlist::where('user_id', $request->user()->id)
                ->where('product_id', $validatedData['product_id'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item already in wishlist',
                ], 409);
            }
            $wishlist = Wishlist::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Item added to wishlist successfully',
                'data' => $wishlist,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to create wishlist',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while creating wishlist',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist)
    {
        try {
            Gate::authorize('view', $wishlist);

            return response()->json([
                'success' => true,
                'message' => 'Wishlist fetched successfully',
                'data' => $wishlist->load('product'),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this wishlist',
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist, Request $request)
    {
        try {
            Gate::authorize('delete', $wishlist);

            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from wishlist successfully',
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this wishlist item',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting wishlist',
            ], 500);
        }
    }
}
