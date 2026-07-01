<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Data Employee</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Employees</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <!-- Filter Form Card -->
    <div class="admin-card mb-6" x-data="{ isOpen: true }">
        <div class="admin-card-header flex justify-between items-center cursor-pointer select-none" @click="isOpen = !isOpen">
            <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-filter mr-2"></i> Filter Employees</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="admin-card-body p-5" x-show="isOpen" x-transition>
            <form action="{{ route('employees.index') }}" method="GET">
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
                    <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm transition-colors">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-adminlte-primary border border-transparent rounded-md font-semibold text-white hover:bg-[#3d6638] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-adminlte-primary sm:text-sm transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Employee List</h3>
        </div>
        <div class="admin-card-body p-0 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->employee_id ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold">{{ $employee->employee_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->email ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->job_title ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->job_level ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->gender ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->location_current_year ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($employee->termination_date)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Past
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center justify-center p-2 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="View History">
                                    <i class="fas fa-history text-sm"></i>
                                </a>
                                @if(!$employee->termination_date)
                                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center justify-center p-2 rounded bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center p-2 rounded bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
