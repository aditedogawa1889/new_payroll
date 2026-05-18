<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Users</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Users</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="admin-card">
        <div class="admin-card-header flex justify-between items-center">
            <h3 class="card-title text-lg font-semibold">List of Users</h3>
            <a href="{{ route('users.create') }}" class="inline-flex items-center bg-adminlte-primary text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-[#3d6638] hover:shadow-lg transition-all duration-200 font-semibold">
                <i class="fas fa-user-plus mr-2 text-lg"></i> Add New User
            </a>
        </div>
        <div class="admin-card-body p-6 overflow-x-auto">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <table id="userTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Access</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                            <div class="flex flex-wrap gap-1">
                                @foreach(($user->id_permission ?? []) as $permId)
                                    @if(isset($permissions[$permId]))
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded text-xs font-bold">
                                            {{ $permissions[$permId]->description }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded text-xs font-bold">
                                {{ count($user->usersMenu?->id_menus ?? []) }} menus
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center space-x-3">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-transform" title="Edit User">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-transform" title="Delete User">
                                    <i class="fas fa-trash-alt text-lg"></i>
                                </button>
                            </form>
                            @endif
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
            $('#userTable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search users..."
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
