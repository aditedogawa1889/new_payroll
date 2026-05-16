<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\User;
use App\Models\UsersMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('usersMenu')->get();
        $permissions = Permission::all()->keyBy('id');
        return view('admin.users.index', compact('users', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $menus = Menu::where('is_parent', 1)
            ->with(['submenus' => function ($q) {
                $q->orderBy('order_menu');
            }])
            ->orderBy('order_menu')
            ->get();
        return view('admin.users.create', compact('permissions', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'id_permission' => $request->input('id_permission', []),
        ]);

        // Simpan akses menu (hanya child menus)
        $menuIds = $request->input('menu_ids', []);
        // Filter: pastikan hanya child menus (is_parent = 0)
        $validMenuIds = Menu::whereIn('id_menu', $menuIds)
            ->where('is_parent', 0)
            ->pluck('id_menu')
            ->toArray();

        UsersMenu::create([
            'id_user'    => $user->id,
            'id_menus'   => array_values($validMenuIds),
            'created_by' => auth()->user()->name,
            'updated_by' => auth()->user()->name,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $permissions = Permission::all();
        $menus = Menu::where('is_parent', 1)
            ->with(['submenus' => function ($q) {
                $q->orderBy('order_menu');
            }])
            ->orderBy('order_menu')
            ->get();

        $userPermissions = $user->id_permission ?? [];
        $userMenuIds     = $user->usersMenu?->id_menus ?? [];

        return view('admin.users.edit', compact('user', 'permissions', 'menus', 'userPermissions', 'userMenuIds'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->id_permission = $request->input('id_permission', []);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update akses menu
        $menuIds = $request->input('menu_ids', []);
        $validMenuIds = Menu::whereIn('id_menu', $menuIds)
            ->where('is_parent', 0)
            ->pluck('id_menu')
            ->toArray();

        UsersMenu::updateOrCreate(
            ['id_user' => $user->id],
            [
                'id_menus'   => array_values($validMenuIds),
                'updated_by' => auth()->user()->name,
            ]
        );

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->usersMenu?->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
