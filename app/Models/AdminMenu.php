<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = 'admin_menus';

    protected $fillable = [
        'parent_id',
        'menu_key',
        'menu_name',
        'menu_group',
        'icon',
        'url',
        'sort_order',
        'status',
    ];

    public function permissions()
    {
        return $this->hasMany(AdminMenuPermission::class, 'admin_menu_id');
    }
}