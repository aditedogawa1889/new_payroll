<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Add New Menu</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menus.index') }}" class="text-adminlte-primary hover:underline">Menus</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Create</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="admin-card">
            <form action="{{ route('menus.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Menu Name</label>
                        <input type="text" name="nama_menu" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">URI / Route Name</label>
                        <input type="text" name="uri" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" placeholder="fas fa-star">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent Menu</label>
                        <select name="parent_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary">
                            <option value="">None (Top Level)</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id_menu }}">{{ $parent->nama_menu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order</label>
                        <input type="number" name="order_menu" value="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Required Permission</label>
                        <select name="permission_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary">
                            <option value="">None (Visible to all logged-in users)</option>
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center space-x-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_parent" value="1" class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                            <span class="ml-2 text-sm text-gray-600">Is Parent?</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="show_menu" value="1" checked class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                            <span class="ml-2 text-sm text-gray-600">Show in Menu</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                            <span class="ml-2 text-sm text-gray-600">Active</span>
                        </label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-adminlte-primary text-white px-6 py-2 rounded shadow hover:bg-blue-600 transition-colors">
                        Save Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
