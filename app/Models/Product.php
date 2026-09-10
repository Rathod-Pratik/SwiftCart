<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'price',
    'discount',
    'stock',
    'images',
    'category_id',
    'store_link',
    'is_featured',
    'is_limited',
    'is_trending',
    'features',
    'vendor_id',
    'status',
    'visibility',
    'about',
    'menifectures_images',
])]
class Product extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function informationSections(): HasMany
    {
        return $this->hasMany(ProductInformationSection::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
            'image' => 'array',
            'is_featured' => 'boolean',
            'is_limited' => 'boolean',
            'is_trending' => 'boolean',
            'about' => 'array',
            'menifectures_images' => 'array',
        ];
    }
}
