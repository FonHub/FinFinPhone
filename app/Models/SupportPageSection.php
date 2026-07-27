<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportPageSection extends Model
{
    protected $table = 'support_page_sections';

    protected $fillable = [
        'support_page_id',
        'section_key',
        'label',
        'title',
        'description',
        'layout_type',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'support_page_id' => 'integer',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function supportPage()
    {
        return $this->belongsTo(SupportPage::class, 'support_page_id');
    }

    public function items()
    {
        return $this->hasMany(SupportPageSectionItem::class, 'support_page_section_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function activeItems()
    {
        return $this->hasMany(SupportPageSectionItem::class, 'support_page_section_id')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }
}