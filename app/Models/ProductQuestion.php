<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $table = 'product_questions';

    protected $fillable = [
        'mobile_brand_id',
        'mobile_product_category_id',
        'question',
        'question_type',
        'general_answer',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public const TYPE_GENERAL = 'general';
    public const TYPE_MODEL_SPECIFIC = 'model_specific';

    public function brand(): BelongsTo
    {
        return $this->belongsTo(MobileBrand::class, 'mobile_brand_id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(MobileProductCategory::class, 'mobile_product_category_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ProductQuestionAnswer::class, 'product_question_id');
    }
}