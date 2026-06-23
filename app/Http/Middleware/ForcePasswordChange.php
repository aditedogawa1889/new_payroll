<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->must_change_password) {
                // Allow logout, profile edit view, profile update action, password update action, and assets/debug routes
                if (!$request->routeIs('profile.edit') &&
                    !$request->routeIs('profile.update') &&
                    !$request->routeIs('password.update') &&
                    !$request->routeIs('logout') &&
                    !$request->is('_debugbar*') &&
                    !$request->is('__vite_ping') &&
                    !$request->is('images/*') &&
                    !$request->is('css/*') &&
                    !$request->is('js/*')
                ) {
                    return redirect()->route('profile.edit')
                        ->with('warning', 'Anda harus mengubah password Anda terlebih dahulu sebelum dapat mengakses menu.');
                }
            }
        }

        return $next($request);
    }
}
