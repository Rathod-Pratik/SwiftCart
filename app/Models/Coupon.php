<?php

namespace App\Models;

use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'expires_at',
        'is_active',
        'number_of_coupons',
    ];

    public function Order()
    {
        return $this->hasMany(Order::class);
    }
}
