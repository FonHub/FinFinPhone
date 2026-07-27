<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellMethodTransitStation extends Model
{
    protected $table = 'sell_method_transit_stations';

    protected $fillable = [
        'sell_method_id',
        'transit_station_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'sell_method_id' => 'integer',
        'transit_station_id' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function sellMethod()
    {
        return $this->belongsTo(SellMethod::class, 'sell_method_id');
    }

    public function transitStation()
    {
        return $this->belongsTo(TransitStation::class, 'transit_station_id');
    }
}