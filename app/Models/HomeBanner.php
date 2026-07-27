<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    use HasFactory;

    protected $table = 'home_banners';

    protected $fillable = [
        'desktop_image',
        'mobile_image',
    ];

    public $timestamps = true;

    public function getDesktopImageUrlAttribute()
    {
        return $this->desktop_image ? asset('storage/' . $this->desktop_image) : null;
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->mobile_image ? asset('storage/' . $this->mobile_image) : null;
    }
}