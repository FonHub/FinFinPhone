<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetailStep extends Model
{
    protected $fillable = [
        'sale_detail_tab_id',
        'step_label',
        'title',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function tab()
    {
        return $this->belongsTo(SaleDetailTab::class, 'sale_detail_tab_id');
    }
}