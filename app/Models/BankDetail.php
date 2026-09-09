<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
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
#[Hidden(['account_number'])]
class BankDetail extends Model
{
    protected $table = 'vendor_bank_details';

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
