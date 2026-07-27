<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetailSection extends Model
{
    protected $fillable = [
        'page_key',
        'title',
        'sub_title',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function tabs()
    {
        return $this->hasMany(SaleDetailTab::class)->orderBy('sort_order')->orderBy('id');
    }
}