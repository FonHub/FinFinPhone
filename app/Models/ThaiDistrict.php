<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThaiDistrict extends Model
{
    protected $table = 'thai_districts';

    protected $fillable = [
        'thai_province_id',
        'name_th',
        'name_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'thai_province_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(ThaiProvince::class, 'thai_province_id');
    }
}
