<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryIcon extends Model
{
    use HasFactory;

    protected $table = 'product_category_icons';

    protected $fillable = [
        'icon_key',
        'name',
        'label_th',
        'icon_default',
        'icon_active',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
