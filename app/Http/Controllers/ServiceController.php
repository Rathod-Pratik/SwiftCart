<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Notifications\ServiceActionNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    /**
     * Display a listing of services for the authenticated vendor.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $query = Service::query();

            if ($user && $user->isVendor() && ! $user->isAdmin()) {
                $query->where(function ($q) use ($user): void {
                    $q->where('vendor_id', $user->id)
                        ->orWhereNull('vendor_id');
                });
            } elseif ($request->filled('vendor_id')) {
                $query->where('vendor_id', $request->integer('vendor_id'));
            }

            if ($request->filled('search')) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('icon', 'like', "%{$search}%");
                });
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            $query->orderByRaw('CASE WHEN vendor_id = ? THEN 0 WHEN vendor_id IS NULL THEN 1 ELSE 2 END', [$user?->id ?? 0])
                ->latest();

            if ($request->boolean('paginate', false) || $request->filled('page') || $request->filled('per_page')) {
                $perPage = (int) $request->input('per_page', 15);
                $services = $query->paginate($perPage);
            } else {
                $services = $query->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Services retrieved successfully',
                'data' => $services,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve services',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string|max:100',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'nullable|boolean',
            ], [
                'name.required' => 'Service name is required.',
                'icon.required' => 'An icon must be selected from the library.',
            ]);

            $user = $request->user();
            $validated['vendor_id'] = $user?->id;
            $validated['is_active'] = $request->boolean('is_active', true);

            $service = Service::create($validated);

            try {
                $user?->notify(new ServiceActionNotification($service->name, 'created'));
            } catch (\Throwable $notifError) {
                Log::warning('Failed sending service created notification: '.$notifError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Service created successfully',
                'data' => $service,
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
                'message' => 'Failed to create service',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified service.
     */
    public function show(Request $request, Service $service): JsonResponse
    {
        $user = $request->user();

        if ($service->vendor_id !== null && $service->vendor_id !== $user?->id && ! $user?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this service',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $service,
        ]);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service): JsonResponse
    {
        try {
            $user = $request->user();

            if ($service->vendor_id !== null && $service->vendor_id !== $user?->id && ! $user?->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this service',
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string|max:100',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'nullable|boolean',
            ], [
                'name.required' => 'Service name is required.',
                'icon.required' => 'An icon must be selected from the library.',
            ]);

            if ($request->has('is_active')) {
                $validated['is_active'] = $request->boolean('is_active');
            }

            if ($service->vendor_id === null && ! $user?->isAdmin()) {
                $validated['vendor_id'] = $user?->id;
            }

            $service->update($validated);

            try {
                $user?->notify(new ServiceActionNotification($service->name, 'updated'));
            } catch (\Throwable $notifError) {
                Log::warning('Failed sending service updated notification: '.$notifError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully',
                'data' => $service,
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
                'message' => 'Failed to update service',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Request $request, Service $service): JsonResponse
    {
        try {
            $user = $request->user();

            if ($service->vendor_id !== null && $service->vendor_id !== $user?->id && ! $user?->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this service',
                ], 403);
            }

            $serviceName = $service->name;
            $service->delete();

            try {
                $user?->notify(new ServiceActionNotification($serviceName, 'deleted'));
            } catch (\Throwable $notifError) {
                Log::warning('Failed sending service deleted notification: '.$notifError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
