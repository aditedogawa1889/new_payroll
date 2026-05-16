<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('is_parent', 'desc')->orderBy('parent_id')->orderBy('order_menu')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::where('is_parent', 1)->orderBy('order_menu')->get();
        return view('admin.menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'order_menu' => 'required|integer',
        ]);

        $data = $request->only(['nama_menu', 'url_menu', 'icon_menu', 'parent_id', 'order_menu', 'is_parent', 'show_menu']);

        // Jika ada parent_id, is_parent = 0; jika parent maka url_menu = javascript:void(0)
        if (!empty($data['parent_id'])) {
            $data['is_parent'] = 0;
        } else {
            $data['is_parent'] = 1;
            $data['url_menu']  = 'javascript:void(0)';
            $data['parent_id'] = null;
        }

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::where('is_parent', 1)->where('id_menu', '!=', $menu->id_menu)->orderBy('order_menu')->get();
        return view('admin.menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'order_menu' => 'required|integer',
        ]);

        $data = $request->only(['nama_menu', 'url_menu', 'icon_menu', 'parent_id', 'order_menu', 'is_parent', 'show_menu']);

        if (!empty($data['parent_id'])) {
            $data['is_parent'] = 0;
        } else {
            $data['is_parent'] = 1;
            $data['url_menu']  = 'javascript:void(0)';
            $data['parent_id'] = null;
        }

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
