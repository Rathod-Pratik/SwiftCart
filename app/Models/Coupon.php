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
        'description',
        'minimum_order_amount',
        'maximum_discount',
        'per_user_limit',
        'starts_at',
    ];

    protected function casts(): array
    {
        return [
            'minimum_order_amount' => 'decimal:2',
            'maximum_discount' => 'decimal:2',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'starts_at' => 'datetime',
        ];
    }

    public function Order()
    {
        return $this->hasMany(Order::class);
    }
}
