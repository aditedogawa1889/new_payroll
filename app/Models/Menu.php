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
        'url_menu',
        'icon_menu',
        'parent_id',
        'order_menu',
        'is_parent',
        'show_menu',
    ];

    /**
     * Get the submenus for the menu.
     */
    public function submenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id_menu');
    }

    /**
     * Get the parent menu.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id_menu');
    }
}
