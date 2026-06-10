<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Loan Details</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('loans.index') }}" class="text-adminlte-primary hover:underline">Loans</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Detail</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <!-- Success and Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200" role="alert">
            <span class="font-medium">Success!</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
            <span class="font-medium">Failed!</span> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Employee & Loan Info Card -->
        <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden lg:col-span-2">
            <div class="card-header bg-white border-b border-gray-100 py-4 px-6 flex justify-between items-center">
                <h3 class="card-title text-gray-700 font-semibold text-lg flex items-center gap-2">
                    <i class="fas fa-info-circle text-green-600"></i>
                    Employee Loan Information
                </h3>
            </div>
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Employee Data</h4>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-700">Name: <span class="font-bold text-gray-900">{{ $loan->employee->employee_name }}</span></p>
                            <p class="text-sm font-medium text-gray-700">NIK: <span class="font-semibold text-gray-800">{{ $loan->employee->employee_id }}</span></p>
                            <p class="text-sm font-medium text-gray-700">Job Title: <span class="text-gray-600">{{ $loan->employee->job_title }} ({{ $loan->employee->job_level }})</span></p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Loan Details</h4>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-700">Total Loan: <span class="font-bold text-green-600">Rp. {{ number_format($loan->loan_amount, 0, ',', '.') }}</span></p>
                            <p class="text-sm font-medium text-gray-700">Loan Date: <span class="font-semibold text-gray-800">{{ $loan->loan_date ? $loan->loan_date->format('Y-m-d') : '-' }}</span></p>
                            <p class="text-sm font-medium text-gray-700">Status: 
                                @if($loan->loan_status == 1)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Active</span>
                                @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <h4 class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Loan Description</h4>
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[60px]">{{ $loan->loan_description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>

        <!-- Summary & Upload Schedule Card -->
        <div class="flex flex-col gap-6">
            <!-- Summary stats -->
            <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden">
                <div class="card-body p-6 space-y-4">
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase block mb-1">Remaining Loan Balance</span>
                        <div class="text-2xl font-extrabold text-red-600">Rp. {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Total Paid</span>
                            <span class="text-sm font-bold text-green-600">Rp. {{ number_format($totalPaid, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Unpaid</span>
                            <span class="text-sm font-bold text-gray-700">Rp. {{ number_format($totalUnpaid, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload schedule form -->
            <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden flex-grow">
                <div class="card-header bg-white border-b border-gray-100 py-4 px-6">
                    <h3 class="card-title text-gray-700 font-semibold text-md flex items-center gap-2">
                        <i class="fas fa-file-excel text-green-600"></i>
                        Upload Installment Schedule
                    </h3>
                </div>
                <div class="card-body p-6 space-y-4">
                    <div class="bg-blue-50/50 border border-blue-100 text-blue-800 p-3.5 rounded-lg text-xs space-y-2">
                        <p class="font-semibold flex items-center gap-1.5"><i class="fas fa-info-circle text-blue-600"></i> Upload Guide:</p>
                        <ol class="list-decimal pl-4 space-y-1">
                            <li>Download the Excel template using the button below.</li>
                            <li>Fill in the installment details per row (month, year, amount).</li>
                            <li>Upload the completed Excel file using the form below.</li>
                        </ol>
                    </div>

                    <a href="{{ route('loans.download-template', $loan->loan_id) }}" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-gray-300 text-xs font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                        <i class="fas fa-download text-green-600"></i> Download Excel Template
                    </a>

                    <form action="{{ route('loans.import-schedule', $loan->loan_id) }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-gray-100 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Select Excel File (.xlsx)</label>
                            <input type="file" name="file" accept=".xlsx, .xls" class="block w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-200 rounded-md p-1 bg-gray-50" required>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-colors">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Schedules Table Card -->
    <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden">
        <div class="card-header bg-white border-b border-gray-100 py-4 px-6">
            <h3 class="card-title text-gray-700 font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-calendar-alt text-green-600"></i>
                Installment Payment Schedule
            </h3>
        </div>
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-hover table-striped w-full mb-0 text-sm">
                <thead class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Month & Year</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Installment Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Remaining Balance</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Amount Paid</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Payment Date</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divider-y divider-gray-100">
                    @forelse($loan->schedules as $index => $schedule)
                        <tr class="hover:bg-gray-50/40 transition-colors border-b border-gray-100">
                            <td class="px-6 py-4 align-middle">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 align-middle font-medium text-gray-800">
                                {{ DateTime::createFromFormat('!m', $schedule->month_number)->format('F') }} {{ $schedule->year_number }}
                            </td>
                            <td class="px-6 py-4 align-middle font-semibold text-gray-700">Rp. {{ number_format($schedule->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-gray-600">Rp. {{ number_format($schedule->remaining_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-green-600">Rp. {{ number_format($schedule->paid_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-gray-500">
                                {{ $schedule->payment_date ? $schedule->payment_date->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 align-middle text-center">
                                @if($schedule->payment_status == 1)
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Unpaid
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                    <p class="font-medium text-gray-500">Installment schedule has not been uploaded. Please upload the Excel file above.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
