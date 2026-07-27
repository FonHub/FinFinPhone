<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileModel extends Model
{
    use HasFactory;

    protected $table = 'mobile_models';

    protected $fillable = [
        'mobile_brand_id',
        'mobile_product_category_id',
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(MobileBrand::class, 'mobile_brand_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(MobileProductCategory::class, 'mobile_product_category_id');
    }

    public function prices()
    {
        return $this->hasMany(MobileModelPrice::class, 'mobile_model_id');
    }

    public function gradePrices()
    {
        return $this->hasMany(MobileModelPriceGrade::class, 'mobile_model_id');
    }
}