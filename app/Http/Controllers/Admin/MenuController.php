<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['parent', 'permission'])->orderBy('order_menu')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::where('is_parent', 1)->get();
        $permissions = Permission::all();
        return view('admin.menus.create', compact('parents', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'order_menu' => 'required|integer',
        ]);

        Menu::create($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::where('is_parent', 1)->get();
        $permissions = Permission::all();
        return view('admin.menus.edit', compact('menu', 'parents', 'permissions'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'order_menu' => 'required|integer',
        ]);

        $menu->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
