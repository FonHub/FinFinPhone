<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTimeSlot extends Model
{
    protected $table = 'service_time_slots';

    protected $fillable = [
        'label',
        'start_time',
        'end_time',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
