<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellMethodBranch extends Model
{
    protected $table = 'sell_method_branches';

    protected $fillable = [
        'sell_method_id',
        'branch_id',
        'province_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sell_method_id' => 'integer',
        'branch_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function sellMethod()
    {
        return $this->belongsTo(SellMethod::class, 'sell_method_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}