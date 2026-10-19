<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VendorRegistrationController extends Controller
{
    /**
     * Show the vendor registration form or current status.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $vendor = $user ? $user->vendor : null;

        return view('auth.become-vendor', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Submit a new vendor registration application.
     */
    public function store(StoreVendorRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'You must be logged in to apply as a vendor.');
        }

        if ($user->vendor()->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You have already submitted a vendor application.',
                ], 422);
            }

            return back()->withErrors([
                'store_name' => 'You have already submitted a vendor application.',
            ]);
        }

        $validated = $request->validated();

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('vendor-logos', 'public');
        }

        $documentPath = null;
        if ($request->hasFile('verification_document')) {
            // Store sensitive verification documents securely on local private disk
            $documentPath = $request->file('verification_document')->store('verification_documents', 'local');
        }

        // Always save with status 'pending' by default. Users cannot set status or self-promote to admin.
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            'phone' => $validated['phone'],
            'business_address' => $validated['business_address'],
            'business_type' => $validated['business_type'],
            'status' => 'pending',
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'business_registration_number' => $validated['business_registration_number'] ?? null,
            'tax_identification_number' => $validated['tax_identification_number'] ?? null,
            'verification_document' => $documentPath,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Vendor application submitted successfully and is pending approval.',
                'vendor' => $vendor,
            ], 201);
        }

        return redirect()->route('vendor.register')
            ->with('status', 'Vendor application submitted successfully and is pending approval.');
    }

    /**
     * Get the current user's vendor application.
     */
    public function currentApplication(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $vendor = $user->vendor;

        if (! $vendor) {
            return response()->json(['message' => 'No vendor application found'], 404);
        }

        return response()->json([
            'success' => true,
            'vendor' => $vendor,
        ]);
    }

    /**
     * Admin: List vendor applications with filtering and pagination.
     */
    public function indexApplications(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'status' => ['nullable', 'string', 'in:pending,approved,rejected'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $perPage = $validated['per_page'] ?? 10;
        $status = $validated['status'] ?? null;
        $search = $validated['search'] ?? null;

        $vendors = Vendor::query()
            ->with(['user'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('store_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('business_type', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $vendors,
        ]);
    }

    /**
     * Admin: Show a vendor application.
     */
    public function showApplication(Vendor $vendor): JsonResponse
    {
        $vendor->load('user');

        return response()->json([
            'success' => true,
            'vendor' => $vendor,
        ]);
    }

    /**
     * Admin: Update vendor application status (approve or reject).
     */
    public function updateStatus(Request $request, Vendor $vendor): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,rejected'],
        ], [
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either approved or rejected.',
        ]);

        Gate::authorize('updateStatus', $vendor);

        $newStatus = $validated['status'];
        $vendor->update(['status' => $newStatus]);

        if ($newStatus === 'approved') {
            $vendor->user->update(['role' => 'vendor']);
        } elseif ($newStatus === 'rejected') {
            $vendor->user->update(['role' => 'customer']);
        }

        return response()->json([
            'message' => "Vendor application {$newStatus} successfully.",
            'vendor' => $vendor->fresh(['user']),
        ]);
    }

    /**
     * Securely download or view the vendor verification document.
     */
    public function downloadDocument(Vendor $vendor): StreamedResponse|JsonResponse
    {
        Gate::authorize('downloadDocument', $vendor);

        if (! $vendor->verification_document || ! Storage::disk('local')->exists($vendor->verification_document)) {
            return response()->json(['message' => 'Verification document not found.'], 404);
        }

        return Storage::disk('local')->download($vendor->verification_document);
    }
}
