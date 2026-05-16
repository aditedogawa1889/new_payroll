<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    /**
     * Bind data to the view.
     * Menu yang ditampilkan diambil dari tabel users_menus berdasarkan user yang login.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();
        if (!$user) {
            $view->with('sidebarMenus', collect());
            return;
        }

        // Ambil ID menu yang diperbolehkan untuk user ini (hanya child menus)
        $userMenuIds = $user->getMenuIds();

        // Ambil parent menus yang punya setidaknya satu child yang diizinkan
        $sidebarMenus = Menu::where('is_parent', 1)
            ->where('show_menu', 1)
            ->with(['submenus' => function ($q) use ($userMenuIds) {
                $q->where('show_menu', 1)
                  ->whereIn('id_menu', $userMenuIds)
                  ->orderBy('order_menu');
            }])
            ->orderBy('order_menu')
            ->get()
            ->filter(fn($menu) => $menu->submenus->count() > 0);

        $view->with('sidebarMenus', $sidebarMenus);
    }
}
