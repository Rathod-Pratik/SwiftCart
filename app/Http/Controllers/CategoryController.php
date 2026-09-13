<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    private array $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
        'icon' => 'required|string|max:100',
        'status' => 'required|in:active,inactive',
    ];

    private array $messages = [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name may not be greater than 255 characters.',
        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be a string.',
        'description.max' => 'The description may not be greater than 255 characters.',
        'slug.required' => 'The slug field is required.',
        'slug.string' => 'The slug must be a string.',
        'slug.max' => 'The slug may not be greater than 255 characters.',
        'slug.unique' => 'The slug has already been taken.',
        'icon.required' => 'The icon field is required.',
        'icon.string' => 'The icon must be a string.',
        'icon.max' => 'The icon may not be greater than 100 characters.',
        'status.required' => 'The status field is required.',
        'status.boolean' => 'The status field must be true or false.',
    ];

    /**
     * Get all categories
     */
    public function index(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
            ], [
                'page.integer' => 'The page must be an integer.',
                'page.min' => 'The page must be at least 1.',
                'per_page.integer' => 'The per_page must be an integer.',
                'per_page.min' => 'The per_page must be at least 1.',
                'per_page.max' => 'The per_page may not be greater than 100.',
            ]);

            $page = $validatedData['page'] ?? 1;
            $per_page = $validatedData['per_page'] ?? 10;

            $cacheKey = "categories:page:{$page}:per_page:{$per_page}";

            $categories = Cache::tags(['categories'])
                ->remember($cacheKey, now()->addMinutes(30), function () use ($per_page) {
                    return Category::latest()->paginate($per_page);
                });

            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully',
                'data' => $categories,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching categories',
            ], 500);
        }
    }

    /**
     * Create a category
     */
    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Category::class);
            $validated = $request->validate($this->rules, $this->messages);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category,
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
                'message' => 'You are not authorized to create category',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error occurred while creating category: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while creating category',
            ], 500);
        }
    }

    /**
     * Update a category
     */
    public function update(Request $request, Category $category)
    {
        try {
            Gate::authorize('update', $category);

            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|max:255',
                'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
                'icon' => 'sometimes|required|string|max:100',
                'status' => 'sometimes|required|boolean',
            ];

            $validated = $request->validate($rules, $this->messages);

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category,
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
                'message' => 'You are not authorized to update category',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error occurred while updating category: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while updating category',
            ], 500);
        }
    }

    /**
     * Delete a category
     */
    public function destroy(Category $category)
    {
        try {
            Gate::authorize('delete', $category);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete category',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error occurred while deleting category: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting category',
            ], 500);
        }
    }
}
