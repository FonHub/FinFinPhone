<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileModelPriceGrade extends Model
{
    use HasFactory;

    protected $table = 'mobile_model_price_grades';

    protected $fillable = [
        'mobile_model_id',
        'grade_master_id',
        'deduct_price',
    ];

    protected $casts = [
        'deduct_price' => 'decimal:2',
    ];

    public function mobileModel()
    {
        return $this->belongsTo(MobileModel::class, 'mobile_model_id');
    }

    public function grade()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_master_id');
    }
}