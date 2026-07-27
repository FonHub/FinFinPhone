<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuestionAnswer extends Model
{
    use HasFactory;

    protected $table = 'product_question_answers';

    protected $fillable = [
        'mobile_model_id',
        'product_question_id',
        'answer',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function mobileModel(): BelongsTo
    {
        return $this->belongsTo(MobileModel::class, 'mobile_model_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ProductQuestion::class, 'product_question_id');
    }
}