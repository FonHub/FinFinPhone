<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransitLine extends Model
{
    protected $table = 'transit_lines';

    protected $fillable = [
        'code',
        'name',
        'operator_name',
        'line_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function stations()
    {
        return $this->hasMany(TransitStation::class, 'line_id');
    }
}