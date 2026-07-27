<?php

namespace App\Helpers;

use App\Models\AdminMenuPermission;
use Illuminate\Support\Facades\Auth;

class AdminPermission
{
    public static function check(string $menuKey, string $action = 'view'): bool
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return false;
        }

        if ((int) ($user->is_super_admin ?? 0) === 1) {
            return true;
        }

        $columnMap = [
            'view' => 'can_view',
            'create' => 'can_create',
            'update' => 'can_update',
            'delete' => 'can_delete',
        ];

        $column = $columnMap[$action] ?? 'can_view';

        return AdminMenuPermission::query()
            ->join('admin_menus', 'admin_menus.id', '=', 'admin_menu_permissions.admin_menu_id')
            ->where('admin_menu_permissions.admin_user_id', $user->id)
            ->where('admin_menus.menu_key', $menuKey)
            ->where("admin_menu_permissions.$column", 1)
            ->exists();
    }
}