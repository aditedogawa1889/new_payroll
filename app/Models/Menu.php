<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu',
        'uri',
        'icon',
        'is_parent',
        'parent_id',
        'order_menu',
        'show_menu',
        'is_active',
        'permission_id',
    ];

    /**
     * Get the submenus for the menu.
     */
    public function submenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id_menu')->orderBy('order_menu');
    }

    /**
     * Get the parent menu.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id_menu');
    }

    /**
     * Get the permission associated with the menu.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class, 'permission_id');
    }
}
