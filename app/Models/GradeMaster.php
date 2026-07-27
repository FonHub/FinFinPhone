<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeMaster extends Model
{
    use HasFactory;

    protected $table = 'grade_masters';

    protected $fillable = [
        'grade_code',
        'grade_name',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function questionOptions(): HasMany
    {
        return $this->hasMany(ProductGradeQuestionOption::class, 'grade_master_id');
    }

    public function modelPriceGrades(): HasMany
    {
        return $this->hasMany(MobileModelPriceGrade::class, 'grade_master_id');
    }
}