<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersMenu extends Model
{
    protected $table = 'users_menus';
    protected $primaryKey = 'id_users_menus';

    protected $fillable = [
        'id_user',
        'id_menus',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'id_menus' => 'array',
        ];
    }

    /**
     * Get the user this menu setting belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
