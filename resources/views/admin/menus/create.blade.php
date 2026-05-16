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

                @if($errors->any())
                    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Menu Name <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent Menu</label>
                        <select name="parent_id" id="parent_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" onchange="toggleUrlField()">
                            <option value="">None (Top Level / Parent)</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id_menu }}" {{ old('parent_id') == $parent->id_menu ? 'selected' : '' }}>{{ $parent->nama_menu }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400">Kosongkan jika menu ini adalah parent/top-level.</p>
                    </div>

                    <div id="url_menu_field">
                        <label class="block text-sm font-medium text-gray-700">URL / Route Name</label>
                        <input type="text" name="url_menu" value="{{ old('url_menu') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" placeholder="contoh: users.index">
                        <p class="mt-1 text-xs text-gray-400">Isi dengan nama route (contoh: <code>users.index</code>). Jika parent, otomatis diisi <code>javascript:void(0)</code>.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Icon (FontAwesome class)</label>
                        <input type="text" name="icon_menu" value="{{ old('icon_menu') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" placeholder="fas fa-star">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order <span class="text-red-500">*</span></label>
                        <input type="number" name="order_menu" value="{{ old('order_menu', 0) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary" required>
                    </div>

                    <div class="flex items-center space-x-6 mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="show_menu" value="1" {{ old('show_menu', '1') ? 'checked' : '' }} class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary">
                            <span class="ml-2 text-sm text-gray-600">Show in Menu</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('menus.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                        Cancel
                    </a>
                    <button type="submit" class="bg-adminlte-primary text-white px-6 py-2.5 rounded-lg shadow hover:bg-[#3d6638] transition-colors font-semibold">
                        Save Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleUrlField() {
            const parentId = document.getElementById('parent_id').value;
            const urlField = document.getElementById('url_menu_field');
            const urlInput = urlField.querySelector('input');
            if (!parentId) {
                // Top level: url otomatis javascript:void(0)
                urlInput.value = 'javascript:void(0)';
                urlInput.readOnly = true;
                urlInput.classList.add('bg-gray-100', 'text-gray-400');
            } else {
                urlInput.readOnly = false;
                urlInput.classList.remove('bg-gray-100', 'text-gray-400');
                if (urlInput.value === 'javascript:void(0)') urlInput.value = '';
            }
        }
        // Run on page load
        toggleUrlField();
    </script>
    @endpush
</x-admin-layout>
