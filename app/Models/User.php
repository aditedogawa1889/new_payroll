<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_permission',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id_permission' => 'array',
        ];
    }

    /**
     * Get the users_menu record associated with this user.
     */
    public function usersMenu(): HasOne
    {
        return $this->hasOne(UsersMenu::class, 'id_user', 'id');
    }

    /**
     * Get permission IDs as array.
     */
    public function getPermissionIds(): array
    {
        $perms = $this->id_permission;
        if (is_string($perms)) {
            return json_decode($perms, true) ?? [];
        }
        return $perms ?? [];
    }

    /**
     * Get accessible menu IDs as array.
     */
    public function getMenuIds(): array
    {
        $menus = $this->usersMenu?->id_menus;
        if (is_string($menus)) {
            return json_decode($menus, true) ?? [];
        }
        return $menus ?? [];
    }

    /**
     * Check if user has a specific permission ID.
     */
    public function hasPermissionId(int $permissionId): bool
    {
        return in_array($permissionId, $this->getPermissionIds());
    }
}
