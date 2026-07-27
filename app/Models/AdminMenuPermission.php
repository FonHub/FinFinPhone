<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMenuPermission extends Model
{
    protected $table = 'admin_menu_permissions';

    protected $fillable = [
        'admin_user_id',
        'admin_menu_id',
        'can_view',
        'can_create',
        'can_update',
        'can_delete',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function menu()
    {
        return $this->belongsTo(AdminMenu::class, 'admin_menu_id');
    }
}