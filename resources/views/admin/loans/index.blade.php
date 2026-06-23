<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Employee Loans</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Loans</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <!-- Filter Form Card -->
    <div class="admin-card mb-6 bg-white rounded-lg shadow-sm border border-gray-100" x-data="{ isOpen: true }">
        <div class="admin-card-header px-5 py-4 border-b border-gray-100 flex justify-between items-center cursor-pointer select-none" @click="isOpen = !isOpen">
            <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-filter mr-2 text-green-600"></i> Filter Loans</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="admin-card-body p-5" x-show="isOpen" x-transition>
            <form action="{{ route('loans.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('loans.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm transition-colors">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-adminlte-primary border border-transparent rounded-md font-semibold text-white hover:bg-[#3d6638] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-adminlte-primary sm:text-sm transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loan List Card -->
    <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden">
        <div class="card-header bg-white border-b border-gray-100 py-4 px-6 flex justify-between items-center">
            <h3 class="card-title text-gray-700 font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-hand-holding-usd text-green-600"></i>
                Employee Loans List
            </h3>
            <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold tracking-wide text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all duration-150 transform hover:-translate-y-0.5 active:translate-y-0">
                <i class="fas fa-plus"></i> Add Loan
            </a>
        </div>
        <div class="card-body p-0 overflow-x-auto">
            @if(session('success'))
                <div class="p-4 text-sm text-green-700 bg-green-100 border-b border-green-200" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 text-sm text-red-700 bg-red-100 border-b border-red-200" role="alert">
                    <span class="font-medium">Failed!</span> {{ session('error') }}
                </div>
            @endif
            <table class="table table-hover table-striped w-full mb-0 text-sm">
                <thead class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Employee Name</th>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Loan Amount</th>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Interest (%)</th>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Loan Date</th>
                        <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3.5 text-center font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-center font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divider-y divider-gray-100">
                    @forelse($loans as $index => $loan)
                        <tr class="hover:bg-gray-50/40 transition-colors border-b border-gray-100">
                            <td class="px-6 py-4 align-middle">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 align-middle font-medium">{{ $loan->employee->employee_id }}</td>
                            <td class="px-6 py-4 align-middle font-semibold text-gray-800">{{ $loan->employee->employee_name }}</td>
                            <td class="px-6 py-4 align-middle font-semibold text-gray-700">Rp. {{ number_format((float) $loan->loan_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-blue-600 font-medium">{{ number_format($loan->loan_interest, 2, ',', '.') }}%</td>
                            <td class="px-6 py-4 align-middle text-gray-600">{{ $loan->loan_date ? $loan->loan_date->format('Y-m-d') : '-' }}</td>
                            <td class="px-6 py-4 align-middle text-gray-500 max-w-[200px] truncate" title="{{ $loan->loan_description }}">{{ $loan->loan_description ?? '-' }}</td>
                            <td class="px-6 py-4 align-middle text-center">
                                @if($loan->loan_status == 1)
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-middle text-center">
                                <a href="{{ route('loans.show', $loan->loan_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold tracking-wide text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all duration-150 transform hover:-translate-y-0.5 active:translate-y-0" title="View Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-hand-holding-usd text-4xl text-gray-300 mb-3"></i>
                                    <p class="font-medium text-gray-500">No employee loan data found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
