<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Fetch top-level menus with submenus and their permissions
        $menus = Menu::with(['submenus', 'permission'])
            ->where('is_active', 1)
            ->where('show_menu', 1)
            ->whereNull('parent_id')
            ->orderBy('order_menu')
            ->get();

        // Filter menus based on user permissions
        $filteredMenus = $menus->filter(function ($menu) use ($user) {
            // If it's a parent, check if any of its submenus are accessible
            if ($menu->is_parent) {
                $menu->setRelation('submenus', $menu->submenus->filter(function ($submenu) use ($user) {
                    $permission = $submenu->permission;
                    return !$permission || $user->can($permission->name);
                }));
                return $menu->submenus->count() > 0;
            }

            // If it's a single menu, check its permission
            $permission = $menu->permission;
            return !$permission || $user->can($permission->name);
        });

        $view->with('sidebarMenus', $filteredMenus);
    }
}
