<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Add New User</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-adminlte-primary hover:underline">Users</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Create</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl">
        <div class="admin-card">
            <form action="{{ route('users.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Info -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Account Information</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                    </div>

                    <!-- Roles & Permissions -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Assign Roles</h4>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                @foreach($roles as $role)
                                <label class="inline-flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                    <span class="ml-2 text-sm font-semibold text-gray-700">{{ $role->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Menu Access Settings (Direct Permissions)</h4>
                            <p class="mt-1 text-xs text-gray-500 mb-4">*Usually inherited from roles, but can be assigned directly here.</p>
                            
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
                                            <div class="ml-10 mt-3 grid grid-cols-1 gap-2 border-l-2 border-gray-200 pl-4">
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
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 border-t pt-6">
                    <a href="{{ route('users.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                        Cancel
                    </a>
                    <button type="submit" class="bg-adminlte-primary text-white px-8 py-2.5 rounded-lg shadow hover:bg-[#3d6638] transition-all duration-200 font-bold">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
