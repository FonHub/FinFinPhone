<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportPage extends Model
{
    protected $table = 'support_pages';

    protected $fillable = [
        'slug',
        'page_type',
        'menu_label',
        'page_title',
        'breadcrumb_title',

        'badge_text',
        'hero_title',
        'hero_description',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',

        'contact_title',
        'contact_description',
        'contact_phone_label',
        'contact_phone',
        'contact_email_label',
        'contact_email',
        'contact_time_label',
        'contact_time',

        'note_icon',
        'note_title',
        'note_description',
        'call_button_text',
        'call_button_url',

        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function sections()
    {
        return $this->hasMany(SupportPageSection::class, 'support_page_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function activeSections()
    {
        return $this->hasMany(SupportPageSection::class, 'support_page_id')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }
}
