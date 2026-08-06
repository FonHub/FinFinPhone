<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileProductCategory extends Model
{
    use HasFactory;

    protected $table = 'mobile_product_categories';

    protected $fillable = [
        'category_name',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function selectedIcon()
    {
        return $this->belongsTo(ProductCategoryIcon::class, 'icon', 'icon_key');
    }

    public function mobileModels()
    {
        return $this->hasMany(MobileModel::class, 'mobile_product_category_id');
    }
    public function gradeQuestions()
    {
        return $this->hasMany(ProductGradeQuestion::class, 'mobile_product_category_id');
    }
    public function brand()
    {
        return $this->belongsTo(
            MobileBrand::class,
            'mobile_brand_id',
            'id'
        );
    }
}
