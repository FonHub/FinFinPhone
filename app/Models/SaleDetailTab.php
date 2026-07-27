<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetailTab extends Model
{
    protected $fillable = [
        'sale_detail_section_id',
        'tab_key',
        'name',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(SaleDetailSection::class, 'sale_detail_section_id');
    }

    public function steps()
    {
        return $this->hasMany(SaleDetailStep::class)->orderBy('sort_order')->orderBy('id');
    }
}