<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceArea extends Model
{
    protected $table = 'service_areas';

    protected $fillable = [
        'thai_province_id',
        'thai_district_id',
        'is_all_districts',
        'province',
        'district',
    ];

    protected $casts = [
        'thai_province_id' => 'integer',
        'thai_district_id' => 'integer',
        'is_all_districts' => 'boolean',
    ];

    public function provinceData(): BelongsTo
    {
        return $this->belongsTo(ThaiProvince::class, 'thai_province_id');
    }

    public function districtData(): BelongsTo
    {
        return $this->belongsTo(ThaiDistrict::class, 'thai_district_id');
    }
}