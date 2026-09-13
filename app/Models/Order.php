<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'subtotal', 'discount', 'discount_code', 'discount_id',
        'shipping_cost', 'tax', 'total_amount', 'order_status', 'payment_method',
        'payment_status', 'transaction_id', 'shipping_name', 'shipping_phone',
        'shipping_address', 'shipping_city', 'shipping_state', 'shipping_postal_code',
        'shipping_country', 'notes',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'discount_code', 'code');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return $this->order_status === 'completed';
    }
}
