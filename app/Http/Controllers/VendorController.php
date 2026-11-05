<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Mail\VendorAccountApproved;
use App\Models\Vendor;
use App\Notifications\VendorApplicationStatusChanged;
use App\Services\S3Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function __construct(protected S3Service $s3Service) {}

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
            ? $this->s3Service->uploadFile($request->file('logo'), 'vendor-logos', $user->id, 'private')
            : null;

        $verificationDocPath = $request->hasFile('verification_document')
            ? $this->s3Service->uploadFile($request->file('verification_document'), 'verification-documents', $user->id, 'private')
            : null;

        $idDocImagePath = $request->hasFile('id_document_image')
            ? $this->s3Service->uploadFile($request->file('id_document_image'), 'vendor-kyc-documents', $user->id, 'private')
            : null;

        $bizCertDocPath = $request->hasFile('business_certificate_document')
            ? $this->s3Service->uploadFile($request->file('business_certificate_document'), 'vendor-kyc-documents', $user->id, 'private')
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
            $vendor->user?->update(['role' => 'vendor']);

            if ($vendor->user?->email) {
                Mail::to($vendor->user)->send(new VendorAccountApproved($vendor->fresh(['user'])));
            }
        } elseif ($newStatus === 'rejected') {
            $vendor->user?->update(['role' => 'customer']);
        }

        try {
            $vendor->user?->notify(new VendorApplicationStatusChanged($vendor, $newStatus));
        } catch (\Throwable $notifError) {
            Log::warning('Failed sending vendor application status notification: '.$notifError->getMessage());
        }

        return response()->json([
            'message' => "Vendor application {$newStatus} successfully.",
            'vendor' => $vendor->fresh(['user']),
        ]);
    }

    /**
     * Return a presigned URL for the vendor verification document.
     */
    public function downloadVerificationDocument(Vendor $vendor): JsonResponse
    {
        Gate::authorize('downloadDocument', $vendor);

        $url = $this->s3Service->signedUrl($vendor->verification_document);

        if (! $url) {
            return response()->json(['message' => 'Verification document not found.'], 404);
        }

        return response()->json(['url' => $url]);
    }

    /**
     * Return a presigned URL for a vendor KYC / registration document.
     */
    public function downloadKycDocument(Vendor $vendor, string $type): JsonResponse
    {
        Gate::authorize('downloadDocument', $vendor);

        $path = match ($type) {
            'id_document' => $vendor->id_document_image,
            'business_certificate' => $vendor->business_certificate_document,
            'verification_document' => $vendor->verification_document,
            default => null,
        };

        $url = $this->s3Service->signedUrl($path);

        if (! $url) {
            return response()->json(['message' => 'Document file not found.'], 404);
        }

        return response()->json(['url' => $url]);
    }

    /**
     * Vendor: Update own KYC / store documents.
     */
    public function updateDocuments(Request $request): JsonResponse
    {
        $user = $request->user();
        $vendor = $user->vendor;

        if (! $vendor) {
            return response()->json(['message' => 'Vendor profile not found.'], 404);
        }

        $request->validate([
            'verification_document' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'id_document_image' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'business_certificate_document' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'logo' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $fields = [
            'verification_document' => 'verification-documents',
            'id_document_image' => 'vendor-kyc-documents',
            'business_certificate_document' => 'vendor-kyc-documents',
            'logo' => 'vendor-logos',
        ];

        $updates = [];

        foreach ($fields as $field => $folder) {
            if ($request->hasFile($field)) {
                // Delete old file from S3
                if ($vendor->{$field}) {
                    $this->s3Service->deleteIfExists($vendor->{$field});
                }
                $newKey = $this->s3Service->uploadFile(
                    $request->file($field),
                    $folder,
                    $user->id,
                    'private'
                );
                if ($newKey) {
                    $updates[$field] = $newKey;
                }
            }
        }

        if (! empty($updates)) {
            $vendor->update($updates);
        }

        return response()->json([
            'message' => 'Documents updated successfully.',
            'vendor' => $vendor->fresh(),
        ]);
    }
}
