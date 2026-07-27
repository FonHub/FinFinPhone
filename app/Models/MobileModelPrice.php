<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileModelPrice extends Model
{
    use HasFactory;

    protected $table = 'mobile_model_prices';

    protected $fillable = [
        'mobile_model_id',
        'capacity',
        'base_price',
        'min_price',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'base_price' => 'decimal:2',
        'min_price' => 'decimal:2',
    ];

    public function mobileModel()
    {
        return $this->belongsTo(MobileModel::class, 'mobile_model_id');
    }
}