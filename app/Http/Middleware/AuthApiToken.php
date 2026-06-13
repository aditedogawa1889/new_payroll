<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fallback: If already authenticated (e.g. via actingAs in tests)
        if (Auth::check()) {
            return $next($request);
        }

        $token = $request->bearerToken() 
            ?? $request->header('X-API-TOKEN') 
            ?? $request->input('api_token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Access token is missing.'
            ], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Access token is invalid.'
            ], 401);
        }

        // Authenticate the user for the current request lifecycle
        Auth::login($user);

        return $next($request);
    }
}
