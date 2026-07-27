<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellMethodParcelSetting extends Model
{
    protected $table = 'sell_method_parcel_settings';

    protected $fillable = [
        'sell_method_id',
        'receiver_name',
        'phone',
        'address',
        'subdistrict',
        'district',
        'province',
        'postcode',
        'remark',
        'is_active',
    ];

    protected $casts = [
        'sell_method_id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function sellMethod()
    {
        return $this->belongsTo(SellMethod::class, 'sell_method_id');
    }
}