<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\S3Service;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct(protected S3Service $s3Service) {}

    /**
     * Display a listing of the products with filters and search.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user('sanctum') ?? $request->user();

            $query = Product::with([
                'category',
                'vendor:id,name,email,image',
                'informationSections',
            ]);

            if (! $user || (! $user->isAdmin() && ! $user->isVendor())) {
                $query->where('status', 'active')->where('visibility', 'public');
            } elseif ($user->isVendor() && ! $user->isAdmin()) {
                if ($request->boolean('my_products')) {
                    $query->where('vendor_id', $user->id);
                } else {
                    $query->where(function ($q) use ($user) {
                        $q->where(function ($sub) {
                            $sub->where('status', 'active')->where('visibility', 'public');
                        })->orWhere('vendor_id', $user->id);
                    });
                }

            }

            if ($request->filled('search')) {
                $search = $request->query('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('features', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->query('category_id'));
            }

            if ($request->filled('vendor_id')) {
                $query->where('vendor_id', $request->query('vendor_id'));
            }

            if ($request->has('is_featured')) {
                $query->where('is_featured', $request->boolean('is_featured'));
            }

            if ($request->has('is_trending')) {
                $query->where('is_trending', $request->boolean('is_trending'));
            }

            if ($request->has('is_limited')) {
                $query->where('is_limited', $request->boolean('is_limited'));
            }

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->query('min_price'));
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->query('max_price'));
            }

            if ($request->filled('status') && $user && ($user->isAdmin() || $user->isVendor())) {
                $query->where('status', $request->query('status'));
            }

            if ($request->filled('visibility') && $user && ($user->isAdmin() || $user->isVendor())) {
                $query->where('visibility', $request->query('visibility'));
            }

            $sortBy = $request->query('sort_by', 'latest');
            match ($sortBy) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'name_asc' => $query->orderBy('name', 'asc'),
                'name_desc' => $query->orderBy('name', 'desc'),
                default => $query->latest(),
            };

            $perPage = (int) $request->query('per_page', 10);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Products fetched successfully',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching products',
            ], 500);
        }
    }

    public function vendorStats(Request $request): JsonResponse
    {
        $products = Product::where('vendor_id', $request->user()->id);

        return response()->json([
            'success' => true,
            'data' => [
                'total' => (clone $products)->count(),
                'active' => (clone $products)->where('status', 'active')->count(),
                'inactive' => (clone $products)->where('status', 'inactive')->count(),
                'out_of_stock' => (clone $products)->where('stock', 0)->count(),
            ],
        ]);
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
            Log::error('Error fetching product: '.$e->getMessage());

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
                'image.*' => ['nullable', function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! ($value instanceof UploadedFile)) {
                        $fail("The {$attribute} must be a valid string or image file.");
                    }
                }],
                'category_id' => 'required|exists:categories,id',
                'is_featured' => 'nullable|boolean',
                'is_limited' => 'nullable|boolean',
                'is_trending' => 'nullable|boolean',
                'features' => 'required',
                'vendor_id' => 'nullable|exists:users,id',
                'status' => 'nullable|in:active,inactive',
                'visibility' => 'nullable|in:public,private',
                'vendor_service_id' => 'nullable|array',
                'vendor_service_id.*' => 'string',
                'about' => 'nullable|array',
                'about.*' => 'string',
                'menifectures_images' => 'nullable|array',
                'menifectures_images.*' => ['nullable', function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! ($value instanceof UploadedFile)) {
                        $fail("The {$attribute} must be a valid string or image file.");
                    }
                }],
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

                foreach ($request->file('image') as $img) {
                    $key = $this->s3Service->uploadFile($img, 'product-images', $user->id, 'private');

                    if (! $key) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload image',
                        ], 500);
                    }

                    $imageKeys[] = $key;
                }

                $existingImages = is_array($request->input('image'))
                    ? array_filter($request->input('image'), 'is_string')
                    : [];
                $validated['image'] = array_values(array_merge($existingImages, $imageKeys));
            }

            if ($request->hasFile('menifectures_images')) {
                $mImageKeys = [];

                foreach ($request->file('menifectures_images') as $mImg) {
                    $key = $this->s3Service->uploadFile($mImg, 'manufacturer-images', $user->id, 'private');

                    if (! $key) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload manufacturer image',
                        ], 500);
                    }

                    $mImageKeys[] = $key;
                }

                $existingMImages = is_array($request->input('menifectures_images'))
                    ? array_filter($request->input('menifectures_images'), 'is_string')
                    : [];
                $validated['menifectures_images'] = array_values(array_merge($existingMImages, $mImageKeys));
            }

            if (isset($validated['features']) && is_array($validated['features'])) {
                $validated['features'] = json_encode($validated['features']);
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
            Log::error('Error creating product: '.$e->getMessage());

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
                'image.*' => ['nullable', function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! ($value instanceof UploadedFile)) {
                        $fail("The {$attribute} must be a valid string or image file.");
                    }
                }],
                'category_id' => 'sometimes|required|exists:categories,id',
                'is_featured' => 'nullable|boolean',
                'is_limited' => 'nullable|boolean',
                'is_trending' => 'nullable|boolean',
                'features' => 'sometimes|required',
                'vendor_id' => 'nullable|exists:users,id',
                'status' => 'nullable|in:active,inactive',
                'visibility' => 'nullable|in:public,private',
                'vendor_service_id' => 'nullable|array',
                'vendor_service_id.*' => 'string',
                'about' => 'nullable|array',
                'about.*' => 'string',
                'menifectures_images' => 'nullable|array',
                'menifectures_images.*' => ['nullable', function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! ($value instanceof UploadedFile)) {
                        $fail("The {$attribute} must be a valid string or image file.");
                    }
                }],
                'information_sections' => 'nullable|array',
                'information_sections.*.title' => 'required_with:information_sections|string|max:255',
                'information_sections.*.features' => 'nullable|array',
            ]);

            $user = $request->user();

            if ($request->hasFile('image')) {
                $imageKeys = [];

                foreach ($request->file('image') as $img) {
                    $key = $this->s3Service->uploadFile($img, 'product-images', $user->id, 'private');

                    if (! $key) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload image',
                        ], 500);
                    }

                    $imageKeys[] = $key;
                }

                $existingImages = is_array($request->input('image'))
                    ? array_filter($request->input('image'), 'is_string')
                    : [];
                $validated['image'] = array_values(array_merge($existingImages, $imageKeys));
            }

            if ($request->hasFile('menifectures_images')) {
                $mImageKeys = [];

                foreach ($request->file('menifectures_images') as $mImg) {
                    $key = $this->s3Service->uploadFile($mImg, 'manufacturer-images', $user->id, 'private');

                    if (! $key) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload manufacturer image',
                        ], 500);
                    }

                    $mImageKeys[] = $key;
                }

                $existingMImages = is_array($request->input('menifectures_images'))
                    ? array_filter($request->input('menifectures_images'), 'is_string')
                    : [];
                $validated['menifectures_images'] = array_values(array_merge($existingMImages, $mImageKeys));
            }

            if (isset($validated['features']) && is_array($validated['features'])) {
                $validated['features'] = json_encode($validated['features']);
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
            Log::error('Error updating product: '.$e->getMessage());

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
            Log::error('Error deleting product: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting product',
            ], 500);
        }
    }
}
