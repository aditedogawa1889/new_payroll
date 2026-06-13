<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    /**
     * Authenticate user and return api_token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required_without_all:email,username|string',
            'email' => 'required_without_all:login,username|email',
            'username' => 'required_without_all:login,email|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $password = $request->input('password');

        // Resolve user by email, username, or login field
        $user = null;
        if ($request->has('email')) {
            $user = User::where('email', $request->input('email'))->first();
        } elseif ($request->has('username')) {
            $user = User::where('username', $request->input('username'))->first();
        } else {
            $login = $request->input('login');
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($field, $login)->first();
        }

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Generate and persist api_token if empty
        if (empty($user->api_token)) {
            $user->api_token = Str::random(60);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'api_token' => $user->api_token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'api_token' => $user->api_token,
                'id_permission' => $user->getPermissionIds(),
            ]
        ], 200);
    }
}
