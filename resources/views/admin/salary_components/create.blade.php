<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Create Salary Component</h2>
    </x-slot>

    <div class="card elevation-2 border-0 rounded-lg max-w-2xl">
        <div class="card-header bg-white border-b border-gray-100 py-4">
            <h3 class="card-title text-gray-700 font-semibold">New Component Details</h3>
        </div>
        <form action="{{ route('salary-components.store') }}" method="POST">
            @csrf
            <div class="card-body p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="nama_component" class="block text-sm font-medium text-gray-700 mb-1">Component Name <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_component" id="nama_component" value="{{ old('nama_component') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" required placeholder="e.g., Basic Salary">
                </div>

                <div class="mb-4">
                    <label for="id_type_component" class="block text-sm font-medium text-gray-700 mb-1">Component Type <span class="text-red-500">*</span></label>
                    <select name="id_type_component" id="id_type_component" class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" required>
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id_type_component }}" {{ old('id_type_component') == $type->id_type_component ? 'selected' : '' }}>
                                {{ $type->nama_type_component }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-adminlte-primary shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>
            <div class="card-footer bg-gray-50/50 py-3 px-6 flex justify-end space-x-3 rounded-b-lg">
                <a href="{{ route('salary-components.index') }}" class="btn btn-default btn-sm px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors shadow-sm font-medium">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-md shadow-sm font-medium transition-colors hover:bg-adminlte-primary-dark">
                    <i class="fas fa-save mr-1"></i> Save Component
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
