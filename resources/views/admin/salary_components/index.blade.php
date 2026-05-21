<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Salary Components</h2>
    </x-slot>

    <div class="card elevation-2 border-0 rounded-lg">
        <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center">
            <h3 class="card-title text-gray-700 font-semibold">Master Salary Components</h3>
            <div class="card-tools">
                <a href="{{ route('salary-components.create') }}" class="btn btn-primary btn-sm rounded-md font-medium text-sm px-3 py-1.5 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add Component
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="p-4 mb-0 text-sm text-green-700 bg-green-100 rounded-none dark:bg-green-200 dark:text-green-800" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
            <table class="table table-hover table-striped w-full mb-0 text-sm">
                <thead class="bg-gray-50/80 text-gray-600 font-semibold border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($components as $index => $comp)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 align-middle">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 align-middle font-medium text-gray-800">{{ $comp->nama_component }}</td>
                            <td class="px-4 py-3 align-middle">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $comp->type?->id_type_component == 1 ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $comp->type?->nama_type_component ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $comp->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $comp->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <div class="flex space-x-2">
                                    <a href="{{ route('salary-components.edit', $comp) }}" class="text-adminlte-primary hover:text-adminlte-primary/80 transition-colors p-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('salary-components.destroy', $comp) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this component?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-cubes text-4xl text-gray-300 mb-3"></i>
                                    <p>No components found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
