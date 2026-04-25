<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Role Management</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Roles</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="admin-card">
        <div class="admin-card-header flex justify-between items-center">
            <h3 class="card-title text-lg font-semibold">List of Roles</h3>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center bg-adminlte-primary text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-[#3d6638] hover:shadow-lg transition-all duration-200 font-semibold">
                <i class="fas fa-shield-alt mr-2 text-lg"></i> Add New Role
            </a>
        </div>
        <div class="admin-card-body p-6 overflow-x-auto">
            <table id="roleTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $role->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-adminlte-primary">{{ $role->name }}</td>
                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions as $perm)
                                    <span class="bg-green-50 text-green-700 border border-green-100 px-2 py-0.5 rounded text-[10px] font-semibold">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center space-x-3">
                            <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-transform" title="Edit Role">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-transform" title="Delete Role">
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
            $('#roleTable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search roles..."
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
