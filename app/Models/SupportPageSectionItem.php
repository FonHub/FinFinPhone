<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportPageSectionItem extends Model
{
    protected $table = 'support_page_section_items';

    protected $fillable = [
        'support_page_section_id',
        'item_no',
        'icon',
        'title',
        'description',
        'link_text',
        'link_url',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'support_page_section_id' => 'integer',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function section()
    {
        return $this->belongsTo(SupportPageSection::class, 'support_page_section_id');
    }
}
