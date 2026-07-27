<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'line_id',
        'password',
        'status',
        'is_super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'is_super_admin' => 'boolean',
        ];
    }

    public function menuPermissions()
    {
        return $this->hasMany(AdminMenuPermission::class, 'admin_user_id');
    }

    public function isSuperAdmin()
    {
        return (int) $this->is_super_admin === 1;
    }

    public function isActive()
    {
        return (int) $this->status === 1;
    }
}