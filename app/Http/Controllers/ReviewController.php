<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\S3Service;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function __construct(protected S3Service $s3Service) {}

    protected array $validationRules = [
        'user_id' => 'sometimes|required|exists:users,id',
        'product_id' => 'required|exists:products,id',
        'images' => 'nullable|array|max:5',
        'images.*' => 'image|max:2048',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    protected array $validationMessages = [
        'user_id.required' => 'User ID is required.',
        'user_id.exists' => 'User ID must exist in the users table.',
        'product_id.required' => 'Product ID is required.',
        'product_id.exists' => 'Product ID must exist in the products table.',
        'images.array' => 'Images must be uploaded as an array.',
        'images.max' => 'You may upload up to 5 images.',
        'images.*.image' => 'Each file must be an image.',
        'images.*.max' => 'Each image cannot exceed 2048 KB.',
        'rating.required' => 'Rating is required.',
        'rating.integer' => 'Rating must be an integer.',
        'rating.min' => 'Rating must be at least 1.',
        'rating.max' => 'Rating cannot be greater than 5.',
        'comment.string' => 'Comment must be a string.',
        'comment.max' => 'Comment cannot exceed 1000 characters.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'nullable|exists:products,id',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
            ], [
                'product_id.exists' => 'Product ID must exist in the products table.',
                'page.integer' => 'Page must be an integer.',
                'page.min' => 'Page must be at least 1.',
                'per_page.integer' => 'Per page must be an integer.',
                'per_page.min' => 'Per page must be at least 1.',
                'per_page.max' => 'Per page cannot exceed 100.',
            ]);

            $productId = $validatedData['product_id'] ?? 'all';
            $page = $validatedData['page'] ?? 1;
            $perPage = $validatedData['per_page'] ?? 10;

            $cacheKey = "reviews:product:{$productId}:page:{$page}:per_page:{$perPage}";

            // Tag by specific product when filtered, otherwise fall back to a general tag
            $tag = $productId !== 'all' ? "reviews:product:{$productId}" : 'reviews:all';

            $reviews = Cache::tags([$tag])->remember($cacheKey, now()->addMinutes(15), function () use ($request, $productId) {
                $query = Review::with(['user:id,name,email,image', 'product:id,name']);

                if ($productId !== 'all') {
                    $query->where('product_id', $productId);
                }

                return $query->latest()->paginate($request->input('per_page', 10));
            });

            if ($reviews->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No reviews found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reviews fetched successfully',
                'data' => $reviews,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching reviews',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Review::class);

            $validatedData = $request->validate($this->validationRules, $this->validationMessages);
            $validatedData['user_id'] = $request->input('user_id', Auth::id());

            if ($request->hasFile('images')) {
                $validatedData['images'] = [];

                foreach ($request->file('images') as $file) {
                    $key = $this->s3Service->generateKey(
                        'reviews',
                        $validatedData['user_id'],
                        $file->getClientOriginalName()
                    );
                    $this->s3Service->uploadFromServer($key, $file->getContent(), 'public');
                    $validatedData['images'][] = $key;
                }
            }

            $review = Review::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review,
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
                'message' => 'You are not authorized to create reviews',
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        try {
            Gate::authorize('update', $review);

            $rules = [
                'user_id' => 'sometimes|required|exists:users,id',
                'product_id' => 'sometimes|required|exists:products,id',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|max:2048',
                'rating' => 'sometimes|required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ];

            $validatedData = $request->validate($rules, $this->validationMessages);

            if ($request->hasFile('images')) {
                $validatedData['images'] = [];

                foreach ($request->file('images') as $file) {
                    $key = $this->s3Service->generateKey(
                        'reviews',
                        $review->user_id,
                        $file->getClientOriginalName()
                    );
                    $this->s3Service->uploadFromServer($key, $file->getContent(), 'public');
                    $validatedData['images'][] = $key;
                }
            }

            $review->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review,
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
                'message' => 'You are not authorized to update this review',
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        try {
            Gate::authorize('delete', $review);

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this review',
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
