<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCodeUsage extends Model
{
    use HasFactory;

    protected $table = 'bonus_code_usages';

    protected $fillable = [
        'bonus_code_id',
        'user_id',
        'estimate_price',
        'bonus_amount',
        'used_at',
    ];

    protected $casts = [
        'estimate_price' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    public function bonusCode()
    {
        return $this->belongsTo(BonusCode::class, 'bonus_code_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
