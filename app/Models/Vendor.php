<?php

namespace App\Models;

use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'store_name',
    'business_name',
    'phone',
    'business_address',
    'business_type',
    'status',
    'description',
    'logo',
    'business_registration_number',
    'tax_identification_number',
    'verification_document',
    'id_document_type',
    'id_number',
    'id_document_image',
    'business_certificate_document',
    'pickup_address',
    'pickup_city',
    'pickup_state',
    'pickup_pincode',
    'support_email',
    'alternate_phone',
])]
class Vendor extends Model
{
    /** @use HasFactory<VendorFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'vendor_id', 'user_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getBusinessNameAttribute(): ?string
    {
        return $this->attributes['store_name'] ?? null;
    }

    public function setBusinessNameAttribute(?string $value): void
    {
        $this->attributes['store_name'] = $value;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->attributes['business_address'] ?? null;
    }

    public function setAddressAttribute(?string $value): void
    {
        $this->attributes['business_address'] = $value;
    }
}
