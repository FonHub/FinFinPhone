<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCode extends Model
{
    use HasFactory;

    protected $table = 'bonus_codes';

    protected $fillable = [
        'code',
        'name',
        'bonus_type',
        'bonus_value',
        'max_bonus_amount',
        'min_estimate_price',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'starts_at',
        'ends_at',
        'description',
        'status',
    ];

    protected $casts = [
        'bonus_value' => 'decimal:2',
        'max_bonus_amount' => 'decimal:2',
        'min_estimate_price' => 'decimal:2',
        'usage_limit' => 'integer',
        'per_user_limit' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => 'boolean',
    ];

    public const TYPE_FIXED = 'fixed';
    public const TYPE_PERCENT = 'percent';

    public function usages()
    {
        return $this->hasMany(BonusCodeUsage::class, 'bonus_code_id');
    }

    public function getBonusTypeTextAttribute(): string
    {
        return $this->bonus_type === self::TYPE_PERCENT ? 'เปอร์เซ็นต์' : 'จำนวนเงิน';
    }

    public function getStatusTextAttribute(): string
    {
        return (int) $this->status === 1 ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    }

    public function isAvailable(?float $estimatePrice = null, ?int $userId = null): bool
    {
        if ((int) $this->status !== 1) {
            return false;
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && now()->gt($this->ends_at)) {
            return false;
        }

        if ($this->usage_limit !== null && (int) $this->used_count >= (int) $this->usage_limit) {
            return false;
        }

        if ($estimatePrice !== null && $estimatePrice < (float) $this->min_estimate_price) {
            return false;
        }

        if ($userId !== null && $this->per_user_limit !== null) {
            $usedByUser = $this->usages()
                ->where('user_id', $userId)
                ->count();

            if ($usedByUser >= (int) $this->per_user_limit) {
                return false;
            }
        }

        return true;
    }

    public function calculateBonus(float $estimatePrice): float
    {
        if ($this->bonus_type === self::TYPE_PERCENT) {
            $bonus = ($estimatePrice * (float) $this->bonus_value) / 100;

            if ($this->max_bonus_amount !== null) {
                $bonus = min($bonus, (float) $this->max_bonus_amount);
            }

            return round($bonus, 2);
        }

        return round((float) $this->bonus_value, 2);
    }
}