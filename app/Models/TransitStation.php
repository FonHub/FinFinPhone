<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransitStation extends Model
{
    protected $table = 'transit_stations';

    protected $fillable = [
        'line_id',
        'station_code',
        'name_th',
        'name_en',
        'province_name',
        'district_name',
        'latitude',
        'longitude',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'line_id' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function line()
    {
        return $this->belongsTo(TransitLine::class, 'line_id');
    }

    public function sellMethodTransitStations()
    {
        return $this->hasMany(SellMethodTransitStation::class, 'transit_station_id');
    }

    public function sellMethods()
    {
        return $this->belongsToMany(
            SellMethod::class,
            'sell_method_transit_stations',
            'transit_station_id',
            'sell_method_id'
        )->withPivot([
            'is_active',
            'sort_order',
        ])->withTimestamps();
    }
}