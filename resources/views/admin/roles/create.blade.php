<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Add New Role</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-adminlte-primary hover:underline">Roles</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Create</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="admin-card">
            <form action="{{ route('roles.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Role Name</label>
                    <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required placeholder="e.g. Admin, Manager, etc.">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Access Settings</label>
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @foreach($menus as $menu)
                            <div class="border rounded-lg p-4 bg-gray-50/50">
                                <label class="flex items-center font-bold text-gray-800 cursor-pointer">
                                    @if($menu->permission)
                                        <input type="checkbox" name="permissions[]" value="{{ $menu->permission->name }}" class="mr-3 rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                    @endif
                                    <i class="{{ $menu->icon }} w-5 text-center mr-2 text-gray-500"></i> {{ $menu->nama_menu }}
                                </label>
                                
                                @if($menu->submenus->count() > 0)
                                    <div class="ml-10 mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 border-l-2 border-gray-200 pl-4">
                                        @foreach($menu->submenus as $submenu)
                                            @if($submenu->permission)
                                                <label class="flex items-center text-sm text-gray-600 hover:text-gray-900 cursor-pointer transition-colors">
                                                    <input type="checkbox" name="permissions[]" value="{{ $submenu->permission->name }}" class="mr-3 rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                                    @if($submenu->icon) <i class="{{ $submenu->icon }} w-4 text-center mr-2 text-gray-400"></i> @endif
                                                    {{ $submenu->nama_menu }}
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-adminlte-primary text-white px-6 py-2 rounded shadow hover:bg-blue-600 transition-colors">
                        Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
