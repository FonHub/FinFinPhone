<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageSetting extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_banner_image',
        'hero_background_color',

        'about_section_title',
        'about_image',
        'about_company_title',
        'about_description',

        'why_choose_title',
        'why_choose_description',

        'feature_1_title',
        'feature_1_description',

        'feature_2_title',
        'feature_2_description',

        'feature_3_title',
        'feature_3_description',

        'feature_4_title',
        'feature_4_description',

        'footer_company_name',
        'footer_company_description',

        'contact_phone',
        'contact_email',
        'social_facebook',
        'social_instagram',
        'social_line',
        'social_youtube',
    ];
}
