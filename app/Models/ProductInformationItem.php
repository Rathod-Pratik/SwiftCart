<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['section_id', 'label', 'value', 'sort_order'])]
class ProductInformationItem extends Model
{
    public function section(): BelongsTo
    {
        return $this->belongsTo(ProductInformationSection::class, 'section_id');
    }
}
