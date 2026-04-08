<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // យើងប្រើ role_id ជា Foreign Key
        'phone',
        'profile_picture',
        'is_active',
        'address',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: User belongs to a Role (One-to-Many)
     */
    // ១. ប្តូរឈ្មោះពី role() ទៅ userRole()
    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // ២. កែក្នុង hasRole() ឱ្យហៅតាមឈ្មោះថ្មី
    public function hasRole($roleName)
    {
        // ប្រើ userRole ជំនួស role
        return $this->userRole && $this->userRole->name === $roleName;
    }

    // ៣. កែក្នុង hasPermission() ដែរ
    public function hasPermission($permissionName)
    {
        if (!$this->userRole) {
            return false;
        }
        return $this->userRole->permissions->contains('name', $permissionName);
    }
}
