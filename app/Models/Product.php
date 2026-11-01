<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'price',
    'discount',
    'stock',
    'image',
    'images',
    'category_id',
    'is_featured',
    'is_limited',
    'is_trending',
    'features',
    'vendor_id',
    'status',
    'visibility',
    'vendor_service_id',
    'about',
    'menifectures_images',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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
            'vendor_service_id' => 'array',
            'about' => 'array',
            'menifectures_images' => 'array',
        ];
    }
}
