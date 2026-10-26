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

class VendorController extends Controller
{
    /**
     * Show the vendor registration form or current status.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $vendor = $user ? $user->vendor : null;

        return view('customer.auth.become-vendor', [
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
                'business_name' => 'You have already submitted a vendor application.',
            ]);
        }

        $validated = $request->validated();

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('vendor-logos', 'public')
            : null;

        $verificationDocPath = $request->hasFile('verification_document')
            ? $request->file('verification_document')->store('verification_documents', 'local')
            : null;

        $idDocImagePath = $request->hasFile('id_document_image')
            ? $request->file('id_document_image')->store('vendor-kyc-documents', 'local')
            : null;

        $bizCertDocPath = $request->hasFile('business_certificate_document')
            ? $request->file('business_certificate_document')->store('vendor-kyc-documents', 'local')
            : null;

        $storeName = $validated['store_name'] ?? $validated['business_name'] ?? 'My Store';
        $businessAddress = $validated['business_address'] ?? $validated['address'] ?? '';

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'store_name' => $storeName,
            'phone' => $validated['phone'],
            'business_address' => $businessAddress,
            'business_type' => $validated['business_type'],
            'status' => 'pending',
            'description' => $validated['description'] ?? null,
            'business_registration_number' => $validated['business_registration_number'] ?? null,
            'tax_identification_number' => $validated['tax_identification_number'] ?? null,
            'logo' => $logoPath,
            'verification_document' => $verificationDocPath,
            'id_document_type' => $validated['id_document_type'] ?? null,
            'id_number' => $validated['id_number'] ?? null,
            'id_document_image' => $idDocImagePath,
            'business_certificate_document' => $bizCertDocPath,
            'pickup_address' => $validated['pickup_address'] ?? null,
            'pickup_city' => $validated['pickup_city'] ?? null,
            'pickup_state' => $validated['pickup_state'] ?? null,
            'pickup_pincode' => $validated['pickup_pincode'] ?? null,
            'support_email' => $validated['support_email'] ?? null,
            'alternate_phone' => $validated['alternate_phone'] ?? null,
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
    public function myApplication(Request $request): JsonResponse
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
    public function index(Request $request): JsonResponse
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
                        ->orWhere('id_document_type', 'like', "%{$search}%")
                        ->orWhere('id_number', 'like', "%{$search}%")
                        ->orWhere('pickup_city', 'like', "%{$search}%")
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
    public function show(Vendor $vendor): JsonResponse
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
    public function downloadVerificationDocument(Vendor $vendor): StreamedResponse|JsonResponse
    {
        Gate::authorize('downloadDocument', $vendor);

        if (! $vendor->verification_document || ! Storage::disk('local')->exists($vendor->verification_document)) {
            return response()->json(['message' => 'Verification document not found.'], 404);
        }

        return Storage::disk('local')->download($vendor->verification_document);
    }

    /**
     * Securely download or view any vendor KYC / registration document.
     */
    public function downloadKycDocument(Vendor $vendor, string $type): StreamedResponse|JsonResponse
    {
        Gate::authorize('downloadDocument', $vendor);

        $path = match ($type) {
            'id_document' => $vendor->id_document_image,
            'business_certificate' => $vendor->business_certificate_document,
            'verification_document' => $vendor->verification_document,
            default => null,
        };

        if (! $path || ! Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Document file not found.'], 404);
        }

        return Storage::disk('local')->download($path);
    }
}
