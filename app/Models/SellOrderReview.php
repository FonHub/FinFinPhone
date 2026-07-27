<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellOrderReview extends Model
{
    use SoftDeletes;

    protected $table = 'sell_order_reviews';

    protected $fillable = [
        'sell_order_id',
        'user_id',
        'reviewed_by_type',
        'reviewed_by_admin_id',
        'customer_name',
        'customer_phone',
        'rating',
        'title',
        'comment',
        'image',
        'is_displayed',
        'is_active',
    ];

    protected $casts = [
        'sell_order_id' => 'integer',
        'user_id' => 'integer',
        'reviewed_by_admin_id' => 'integer',
        'rating' => 'integer',
        'is_displayed' => 'boolean',
        'is_active' => 'boolean',
    ];
}