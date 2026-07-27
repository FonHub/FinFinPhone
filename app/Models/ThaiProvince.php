<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThaiProvince extends Model
{
    protected $table = 'thai_provinces';

    protected $fillable = [
        'name_th',
        'name_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(ThaiDistrict::class, 'thai_province_id');
    }
}