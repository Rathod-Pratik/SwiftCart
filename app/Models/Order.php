<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'subtotal', 'discount', 'discount_code',
        'shipping_cost', 'tax', 'total_amount', 'order_status', 'payment_method',
        'payment_status', 'transaction_id', 'shipping_name', 'shipping_phone',
        'shipping_address', 'shipping_city', 'shipping_state', 'shipping_postal_code',
        'shipping_country', 'notes',
    ];

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
