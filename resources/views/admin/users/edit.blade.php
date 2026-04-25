<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Edit User</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-adminlte-primary hover:underline">Users</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Edit</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl">
        <div class="admin-card">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Info -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Account Information</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <h5 class="text-sm font-bold text-gray-600 mb-3">Change Password (Optional)</h5>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" name="password" placeholder="Leave blank to keep current" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roles & Permissions -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Assign Roles</h4>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                @foreach($roles as $role)
                                <label class="inline-flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }} class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                    <span class="ml-2 text-sm font-semibold text-gray-700">{{ $role->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Menu Access Settings</h4>
                            <p class="mt-1 text-xs text-gray-500 mb-4">*Akses yang abu-abu (disabled) didapatkan dari Role. Centang kotak putih untuk memberikan akses tambahan khusus untuk user ini.</p>
                            
                            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                                @foreach($menus as $menu)
                                    <div class="border rounded-lg p-4 bg-gray-50/50">
                                        <label class="flex items-center font-bold text-gray-800 {{ $menu->permission && $user->hasPermissionTo($menu->permission->name) && !in_array($menu->permission->name, $userPermissions) ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}">
                                            @if($menu->permission)
                                                @php
                                                    $hasDirect = in_array($menu->permission->name, $userPermissions);
                                                    $hasInherited = $user->hasPermissionTo($menu->permission->name) && !$hasDirect;
                                                @endphp
                                                <input type="checkbox" name="permissions[]" value="{{ $menu->permission->name }}" 
                                                    {{ $hasDirect || $hasInherited ? 'checked' : '' }} 
                                                    {{ $hasInherited ? 'disabled' : '' }}
                                                    class="mr-3 rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                            @endif
                                            <i class="{{ $menu->icon }} w-5 text-center mr-2 text-gray-500"></i> {{ $menu->nama_menu }}
                                        </label>
                                        
                                        @if($menu->submenus->count() > 0)
                                            <div class="ml-10 mt-3 grid grid-cols-1 gap-2 border-l-2 border-gray-200 pl-4">
                                                @foreach($menu->submenus as $submenu)
                                                    @if($submenu->permission)
                                                        @php
                                                            $subHasDirect = in_array($submenu->permission->name, $userPermissions);
                                                            $subHasInherited = $user->hasPermissionTo($submenu->permission->name) && !$subHasDirect;
                                                        @endphp
                                                        <label class="flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors {{ $subHasInherited ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}">
                                                            <input type="checkbox" name="permissions[]" value="{{ $submenu->permission->name }}" 
                                                                {{ $subHasDirect || $subHasInherited ? 'checked' : '' }}
                                                                {{ $subHasInherited ? 'disabled' : '' }}
                                                                class="mr-3 rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
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
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
