<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'vendor_id',
    'account_holder_name',
    'company_name',
    'bank_name',
    'account_number',
    'account_type',
    'ifsc_code',
    'branch_name',
])]
class VendorBankDetail extends Model
{
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
