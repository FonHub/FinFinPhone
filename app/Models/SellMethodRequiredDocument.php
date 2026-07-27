<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellMethodRequiredDocument extends Model
{
    protected $table = 'sell_method_required_documents';

    protected $fillable = [
        'sell_method_id',
        'document_name',
        'description',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sell_method_id' => 'integer',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function sellMethod()
    {
        return $this->belongsTo(SellMethod::class, 'sell_method_id');
    }
}