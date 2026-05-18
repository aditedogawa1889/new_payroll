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

                @if($errors->any())
                    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Info -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Account Information</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
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

                        <!-- Permission -->
                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Permission Level</h4>
                            <p class="mt-1 text-xs text-gray-500 mb-3">Pilih satu atau lebih permission yang dimiliki user ini.</p>
                            <div class="mt-3 grid grid-cols-1 gap-3">
                                @foreach($permissions as $perm)
                                <label class="inline-flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="id_permission[]" value="{{ $perm->id }}"
                                        {{ in_array($perm->id, old('id_permission', $userPermissions)) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                    <div class="ml-3">
                                        <span class="text-sm font-semibold text-gray-700">{{ $perm->description }}</span>
                                        <span class="ml-2 text-xs text-gray-400">(ID: {{ $perm->id }})</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Menu Access -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Menu Access</h4>
                            <p class="mt-1 text-xs text-gray-500 mb-4">Centang sub-menu yang dapat diakses oleh user ini.</p>

                            <div class="space-y-4 max-h-[580px] overflow-y-auto pr-2">
                                @foreach($menus as $menu)
                                    <div class="border rounded-lg p-4 bg-gray-50/50">
                                        <!-- Parent label (tidak bisa diceklis) -->
                                        <div class="flex items-center font-bold text-gray-800 mb-3">
                                            <i class="{{ $menu->icon_menu ?? 'fas fa-folder' }} w-5 text-center mr-2 text-gray-500"></i>
                                            {{ $menu->nama_menu }}
                                        </div>

                                        @if($menu->submenus->count() > 0)
                                            <div class="ml-6 grid grid-cols-1 gap-2 border-l-2 border-gray-200 pl-4">
                                                @foreach($menu->submenus as $submenu)
                                                    <label class="flex items-center text-sm text-gray-600 hover:text-gray-900 cursor-pointer transition-colors p-1.5 rounded hover:bg-white">
                                                        <input type="checkbox" name="menu_ids[]" value="{{ $submenu->id_menu }}"
                                                            {{ in_array($submenu->id_menu, old('menu_ids', $userMenuIds)) ? 'checked' : '' }}
                                                            class="mr-3 rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                                                        @if($submenu->icon_menu)
                                                            <i class="{{ $submenu->icon_menu }} w-4 text-center mr-2 text-gray-400"></i>
                                                        @endif
                                                        {{ $submenu->nama_menu }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="ml-6 text-xs text-gray-400 italic">Tidak ada sub-menu.</p>
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
