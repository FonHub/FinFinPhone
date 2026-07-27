<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductGradeQuestion extends Model
{
    use HasFactory;

    protected $table = 'product_grade_questions';

    protected $fillable = [
        'mobile_brand_id',
        'mobile_product_category_id',
        'question_title',
        'description',
        'answer_type',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(MobileBrand::class, 'mobile_brand_id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(MobileProductCategory::class, 'mobile_product_category_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductGradeQuestionOption::class, 'product_grade_question_id');
    }
}