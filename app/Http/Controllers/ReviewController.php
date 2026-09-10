<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\S3Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function __construct(protected S3Service $s3Service) {}

    protected array $validationRules = [
        'user_id' => 'required|exists:users,id',
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
    public function index()
    {
        try {
            $validatedData = request()->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
            ], [
                'page.min' => 'Page must be at least 1.',
                'per_page.min' => 'Per page must be at least 1.',
                'per_page.max' => 'Per page cannot exceed 100.',
            ]);

            $Reviews = Review::find(request()->input('product_id'))
                ->paginate($validatedData['per_page'] ?? 10);

            if (! $Reviews) {
                return response()->json([
                    'success' => false,
                    'message' => 'No reviews found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reviews fetched successfully',
                'data' => $Reviews,
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
            $validatedData = $request->validate($this->validationRules, $this->validationMessages);

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
            $validatedData = $request->validate($this->validationRules, $this->validationMessages);

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
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
