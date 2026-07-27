<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGradeQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'product_grade_question_options';

    protected $fillable = [
        'product_grade_question_id',
        'option_title',
        'icon_key',
        'grade_master_id',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(ProductGradeQuestion::class, 'product_grade_question_id');
    }

    public function grade()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_master_id');
    }
}