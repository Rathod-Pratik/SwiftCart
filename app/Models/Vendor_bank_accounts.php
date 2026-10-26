<?php

namespace App\Models;

use Database\Factories\VendorBankAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBankAccount extends Model
{
    /** @use HasFactory<VendorBankAccountFactory> */
    use HasFactory;

    protected $fillable = ['vendor_id', 'account_holder_name', 'account_number', 'ifsc_code', 'verification_status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
