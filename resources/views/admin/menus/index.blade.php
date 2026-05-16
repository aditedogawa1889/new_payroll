<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Menu Management</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Menus</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="admin-card">
        <div class="admin-card-header flex justify-between items-center">
            <h3 class="card-title text-lg font-semibold">List of Menus</h3>
            <a href="{{ route('menus.create') }}" class="inline-flex items-center bg-adminlte-primary text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-[#3d6638] hover:shadow-lg transition-all duration-200 font-semibold">
                <i class="fas fa-plus-circle mr-2 text-lg"></i> Add New Menu
            </a>
        </div>
        <div class="admin-card-body p-6 overflow-x-auto">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <table id="menuTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL / Route</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Show</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($menus as $menu)
                    <tr class="{{ $menu->is_parent ? 'bg-green-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $menu->id_menu }}</td>
                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-900 font-semibold">
                            @if(!$menu->is_parent)
                                <span class="text-gray-300 mr-1">↳</span>
                            @endif
                            @if($menu->icon_menu) <i class="{{ $menu->icon_menu }} mr-2 text-gray-400"></i> @endif
                            {{ $menu->nama_menu }}
                        </td>
                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-500 font-mono text-xs">{{ $menu->url_menu }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($menu->parent)
                                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">{{ $menu->parent->nama_menu }}</span>
                            @else
                                <span class="text-gray-400 italic text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $menu->order_menu }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($menu->is_parent)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Parent</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">Child</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $menu->show_menu ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $menu->show_menu ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center space-x-3">
                            <a href="{{ route('menus.edit', $menu->id_menu) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-transform" title="Edit Menu">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            <form action="{{ route('menus.destroy', $menu->id_menu) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-transform" title="Delete Menu">
                                    <i class="fas fa-trash-alt text-lg"></i>
                                </button>
            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#menuTable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search menus..."
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
