<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileBrand extends Model
{
    use HasFactory;

    protected $table = 'mobile_brands';

    protected $fillable = [
        'name',
        'status',
        'sort_order',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(ProductQuestion::class, 'mobile_brand_id');
    }
    public function gradeQuestions()
    {
        return $this->hasMany(ProductGradeQuestion::class, 'mobile_brand_id');
    }
}
