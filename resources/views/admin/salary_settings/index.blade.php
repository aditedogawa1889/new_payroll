<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Salary Settings</h2>
    </x-slot>

    <!-- Filter Form Card -->
    <div class="admin-card mb-6">
        <div class="admin-card-header flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-filter mr-2"></i> Filter Employees</h3>
        </div>
        <div class="admin-card-body p-5">
            <form action="{{ route('salary-settings.index') }}" method="GET">
                <div class="grid grid-cols-3 gap-6">
                    <!-- NIK Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="nik" class="w-1/3 text-sm font-medium text-gray-700 whitespace-nowrap">NIK</label>
                        <input type="text" name="nik" id="nik" value="{{ request('nik') }}" class="w-2/3 block border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm px-3 py-2 bg-gray-50">
                    </div>
                    
                    <!-- Name Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="name" class="w-1/3 text-sm font-medium text-gray-700 whitespace-nowrap">Name</label>
                        <input type="text" name="name" id="name" value="{{ request('name') }}" class="w-2/3 block border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm px-3 py-2 bg-gray-50">
                    </div>

                    <!-- Status Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="status" class="w-1/3 text-sm font-medium text-gray-700 whitespace-nowrap">Status</label>
                        <select name="status" id="status" class="w-2/3 block border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm px-3 py-2 bg-gray-50">
                            <option value="">-- All Status --</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Past" {{ request('status') == 'Past' ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>

                    <!-- Job Title Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="job_title" class="w-1/3 text-sm font-medium text-gray-700 whitespace-nowrap">Job Title</label>
                        <select name="job_title" id="job_title" class="w-2/3 block border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm px-3 py-2 bg-gray-50">
                            <option value="">-- All Job Titles --</option>
                            @foreach($jobTitles as $title)
                                <option value="{{ $title }}" {{ request('job_title') == $title ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Job Level Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="job_level" class="w-1/3 text-sm font-medium text-gray-700 whitespace-nowrap">Job Level</label>
                        <select name="job_level" id="job_level" class="w-2/3 block border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm px-3 py-2 bg-gray-50">
                            <option value="">-- All Job Levels --</option>
                            @foreach($jobLevels as $level)
                                <option value="{{ $level }}" {{ request('job_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="location" class="w-1/3 text-sm font-medium text-gray-700 whitespace-nowrap">Location</label>
                        <select name="location" id="location" class="w-2/3 block border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm px-3 py-2 bg-gray-50">
                            <option value="">-- All Locations --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('salary-settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm transition-colors">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-adminlte-primary border border-transparent rounded-md font-semibold text-white hover:bg-[#3d6638] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-adminlte-primary sm:text-sm transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">No.</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Job Title</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 uppercase tracking-wider">Job Level</th>
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
                            <td class="px-4 py-3 align-middle">{{ $employee->job_level }}</td>
                            <td class="px-4 py-3 align-middle">
                                <a href="{{ route('salary-settings.edit', $employee->emp_number) }}" class="inline-flex items-center justify-center p-2 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Manage Components">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 bg-gray-50/30">
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
