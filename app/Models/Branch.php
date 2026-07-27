<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';

    protected $fillable = [
        'branch_code',
        'branch_name',
        'phone',
        'address',
        'subdistrict',
        'district',
        'province',
        'postcode',
        'latitude',
        'longitude',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function sellMethodBranches()
    {
        return $this->hasMany(SellMethodBranch::class, 'branch_id');
    }
}