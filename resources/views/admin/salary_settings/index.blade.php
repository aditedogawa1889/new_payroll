<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Salary Settings</h2>
    </x-slot>

    <div class="card elevation-2 border-0 rounded-lg">
        <div class="card-header bg-white border-b border-gray-100 py-4">
            <h3 class="card-title text-gray-700 font-semibold">Employee Salary Settings</h3>
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
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Job Title</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($employees as $index => $employee)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 align-middle">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 align-middle font-medium">{{ $employee->employee_id }}</td>
                            <td class="px-4 py-3 align-middle font-medium text-gray-800">{{ $employee->employee_name }}</td>
                            <td class="px-4 py-3 align-middle">{{ $employee->job_title }}</td>
                            <td class="px-4 py-3 align-middle">
                                <a href="{{ route('salary-settings.edit', $employee->emp_number) }}" class="btn btn-sm btn-info text-white rounded shadow-sm px-3 py-1">
                                    <i class="fas fa-cog mr-1"></i> Manage Components
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                                    <p>No active employees found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
