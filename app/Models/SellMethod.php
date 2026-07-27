<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellMethod extends Model
{
    protected $table = 'sell_methods';

    protected $fillable = [
        'key',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];


    public function parcelSettings()
    {
        return $this->hasMany(SellMethodParcelSetting::class, 'sell_method_id');
    }

    public function requiredDocuments()
    {
        return $this->hasMany(SellMethodRequiredDocument::class, 'sell_method_id');
    }

    public function sellMethodTransitStations()
    {
        return $this->hasMany(SellMethodTransitStation::class, 'sell_method_id');
    }

    public function transitStations()
    {
        return $this->belongsToMany(
            TransitStation::class,
            'sell_method_transit_stations',
            'sell_method_id',
            'transit_station_id'
        )->withPivot([
            'is_active',
            'sort_order',
        ])->withTimestamps();
    }
}