<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika tidak login, biarkan middleware auth yang menangani
        if (!$user) {
            return $next($request);
        }

        // Dashboard selalu bisa diakses
        if ($request->routeIs('dashboard')) {
            return $next($request);
        }

        $routeName = $request->route()->getName();
        if (!$routeName) {
            return $next($request);
        }

        // Cari menu yang sesuai dengan route name saat ini.
        // Kita bandingkan prefix route name (misal: users.index, users.create, users.edit semuanya divalidasi ke users.index)
        $parts = explode('.', $routeName);
        $baseRoute = $parts[0]; // misal: 'users' atau 'menus'

        // Cari menu child yang url_menu-nya mengandung baseRoute (misal: users.index)
        // Jika ada menu yang terdaftar untuk route ini, cek aksesnya.
        // Jika tidak terdaftar di tabel menu, anggap bisa diakses oleh semua user yang login (misal: profile)
        $menu = Menu::where('is_parent', 0)
            ->where('url_menu', 'like', $baseRoute . '.%')
            ->first();

        if ($menu) {
            $allowedMenuIds = $user->getMenuIds();
            if (!in_array($menu->id_menu, $allowedMenuIds)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'Unauthorized access to this menu.'], 403);
                }
                abort(403, 'Anda tidak memiliki akses ke menu ini.');
            }
        }

        return $next($request);
    }
}
