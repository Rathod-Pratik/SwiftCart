<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with filters and search.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user('sanctum') ?? $request->user();

            // Build a stable cache key from every filter + who's asking
            $cacheKeyParts = [
                'user_role' => $user ? ($user->isAdmin() ? 'admin' : ($user->isVendor() ? 'vendor:' . $user->id : 'user:' . $user->id)) : 'guest',
                'my_products' => $request->boolean('my_products'),
                'search' => $request->query('search'),
                'category_id' => $request->query('category_id'),
                'vendor_id' => $request->query('vendor_id'),
                'is_featured' => $request->query('is_featured'),
                'is_trending' => $request->query('is_trending'),
                'is_limited' => $request->query('is_limited'),
                'min_price' => $request->query('min_price'),
                'max_price' => $request->query('max_price'),
                'status' => $request->query('status'),
                'visibility' => $request->query('visibility'),
                'sort_by' => $request->query('sort_by', 'latest'),
                'per_page' => $request->query('per_page', 10),
                'page' => $request->query('page', 1),
            ];

            $cacheKey = 'products:index:' . md5(json_encode($cacheKeyParts));

            $products = Product::with([
                'category',
                'vendor:id,name,email,image',
                'informationSections',
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Products fetched successfully',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching products',
            ], 500);
        }
    }

    /**
     * Display a specific product.
     */
    public function show(Product $product): JsonResponse
    {
        try {
            Gate::authorize('view', $product);

            $cacheKey = "products:show:{$product->id}";

            $cachedProduct = Cache::tags(['products', "product:{$product->id}"])
                ->remember($cacheKey, now()->addMinutes(10), function () use ($product) {
                    $product->load(['category', 'vendor:id,name,email,image', 'informationSections']);

                    return $product;
                });

            return response()->json([
                'success' => true,
                'message' => 'Product fetched successfully',
                'data' => $cachedProduct,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this product',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching product',
            ], 500);
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Gate::authorize('create', Product::class);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|array',
                'image.*' => 'string',
                'category_id' => 'required|exists:categories,id',
                'related_products' => 'nullable|array',
                'related_products.*' => 'integer',
                'store_link' => 'nullable|string|max:255',
                'is_featured' => 'nullable|boolean',
                'is_limited' => 'nullable|boolean',
                'is_trending' => 'nullable|boolean',
                'features' => 'required|string',
                'vendor_id' => 'nullable|exists:users,id',
                'status' => 'nullable|in:active,inactive',
                'visibility' => 'nullable|in:public,private',
                'about' => 'nullable|array',
                'menifectures_images' => 'nullable|array',
                'information_sections' => 'nullable|array',
                'information_sections.*.title' => 'required_with:information_sections|string|max:255',
                'information_sections.*.features' => 'nullable|array',
            ]);

            $user = $request->user();

            // Set vendor_id based on role
            if ($user->isVendor() && ! $user->isAdmin()) {
                $validated['vendor_id'] = $user->id;
            } elseif (! isset($validated['vendor_id'])) {
                $validated['vendor_id'] = $user->id;
            }

            if ($request->hasFile('image')) {
                $imageKeys = [];

                foreach ($request->file('image') as $image) {
                    $key = $this->s3Service->generateKey('product-images', $user->id, $image->getClientOriginalName());

                    $uploaded = $this->s3Service->uploadFromServer(
                        $key,
                        file_get_contents($image->getRealPath())
                    );

                    if (! $uploaded) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload image',
                        ], 500);
                    }

                    $imageKeys[] = $key;
                }

                $validated['images'] = $imageKeys; // store as JSON array in DB, or handle relation
            }

            if (array_key_exists('image', $validated)) {
                $validated['images'] = $validated['image'];
                unset($validated['image']);
            }

            $product = DB::transaction(function () use ($validated) {
                $informationSections = $validated['information_sections'] ?? null;
                unset($validated['information_sections']);

                $product = Product::create($validated);

                if (! empty($informationSections)) {
                    foreach ($informationSections as $section) {
                        $product->informationSections()->create([
                            'title' => $section['title'],
                            'features' => $section['features'] ?? null,
                        ]);
                    }
                }

                return $product;
            });

            $product->load(['category', 'vendor:id,name,email,image', 'informationSections']);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
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
                'message' => 'You are not authorized to create products',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while creating product',
            ], 500);
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            Gate::authorize('update', $product);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'image' => 'nullable|array',
                'image.*' => 'string',
                'category_id' => 'sometimes|required|exists:categories,id',
                'related_products' => 'nullable|array',
                'related_products.*' => 'integer',
                'store_link' => 'nullable|string|max:255',
                'is_featured' => 'nullable|boolean',
                'is_limited' => 'nullable|boolean',
                'is_trending' => 'nullable|boolean',
                'features' => 'sometimes|required|string',
                'vendor_id' => 'nullable|exists:users,id',
                'status' => 'nullable|in:active,inactive',
                'visibility' => 'nullable|in:public,private',
                'about' => 'nullable|array',
                'menifectures_images' => 'nullable|array',
                'information_sections' => 'nullable|array',
                'information_sections.*.title' => 'required_with:information_sections|string|max:255',
                'information_sections.*.features' => 'nullable|array',
            ]);

            $user = $request->user();

            if (array_key_exists('image', $validated)) {
                $validated['images'] = $validated['image'];
                unset($validated['image']);
            }

            // Prevent vendor from changing vendor_id
            if (! $user->isAdmin()) {
                unset($validated['vendor_id']);
            }

            DB::transaction(function () use ($product, $validated) {
                $hasSections = array_key_exists('information_sections', $validated);
                $informationSections = $validated['information_sections'] ?? null;
                unset($validated['information_sections']);

                $product->update($validated);

                if ($hasSections) {
                    $product->informationSections()->delete();

                    if (! empty($informationSections)) {
                        foreach ($informationSections as $section) {
                            $product->informationSections()->create([
                                'title' => $section['title'],
                                'features' => $section['features'] ?? null,
                            ]);
                        }
                    }
                }
            });

            $product->load(['category', 'vendor:id,name,email,image', 'informationSections']);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product,
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
                'message' => 'You are not authorized to update this product',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while updating product',
            ], 500);
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            Gate::authorize('delete', $product);

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this product',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting product',
            ], 500);
        }
    }
}
