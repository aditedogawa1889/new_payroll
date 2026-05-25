<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class LogActivityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Skip validation if running unit tests
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        // 2. Skip validation for ignored routes
        if ($this->shouldExclude($request)) {
            return $next($request);
        }

        // 3. Enforce Geolocation cookies presence and validity
        $latitude = $request->cookie('latitude') ?? ($_COOKIE['latitude'] ?? null);
        $longitude = $request->cookie('longitude') ?? ($_COOKIE['longitude'] ?? null);

        $hasLocation = !is_null($latitude) && !is_null($longitude) && 
                       is_numeric($latitude) && is_numeric($longitude) &&
                       $latitude >= -90 && $latitude <= 90 &&
                       $longitude >= -180 && $longitude <= 180;

        if (!$hasLocation) {
            // Block state-changing or AJAX requests with 403 Forbidden
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses lokasi wajib diaktifkan untuk melakukan tindakan ini.'
                ], 403);
            }

            // Render standalone blocker view for GET requests
            return response()->view('layouts.partials.geolocation_standalone');
        }

        // Let the request pass to authenticate and resolve the route
        $response = $next($request);

        // 4. Log the activity after successful execution
        try {
            $route = $request->route();
            $controllerName = null;
            $funcName = null;
            
            if ($route) {
                $action = $route->getAction();
                if (isset($action['controller'])) {
                    $controllerWithMethod = $action['controller'];
                    $parts = explode('@', $controllerWithMethod);
                    $controllerName = $parts[0] ?? null;
                    $funcName = $parts[1] ?? null;
                } elseif (isset($action['uses']) && is_string($action['uses'])) {
                    $parts = explode('@', $action['uses']);
                    $controllerName = $parts[0] ?? null;
                    $funcName = $parts[1] ?? null;
                }
            }

            // Exclude sensitive data
            $params = $request->except(['password', 'password_confirmation', '_token', '_method']);
            
            DB::table('log_activity')->insert([
                'method' => $request->method(),
                'uri' => $request->getRequestUri(),
                'controller' => $controllerName,
                'func' => $funcName,
                'params' => !empty($params) ? json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null,
                'created_by' => auth()->check() ? (auth()->user()->email ?? auth()->user()->name) : 'guest',
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log it but do not crash the user request
            logger()->error('Failed to log activity: ' . $e->getMessage());
        }

        return $response;
    }

    private function shouldExclude(Request $request): bool
    {
        return $request->is('_debugbar*') || 
               $request->is('sanctum/*') || 
               $request->is('_telescope*') || 
               $request->is('livewire/*') || 
               $request->is('__vite_ping') || 
               $request->is('images/*') || 
               $request->is('css/*') || 
               $request->is('js/*') ||
               $request->is('favicon.ico') ||
               $request->is('logout') ||
               ($request->route() && $request->route()->getName() === 'logout');
    }
}
