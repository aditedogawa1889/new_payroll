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
                            <p class="text-sm font-medium text-gray-700">Total Loan: <span class="font-bold text-green-600">Rp. {{ number_format((float) $loan->loan_amount, 0, ',', '.') }}</span></p>
                            <p class="text-sm font-medium text-gray-700">Interest Rate: <span class="font-bold text-blue-600">{{ number_format($loan->loan_interest, 2, ',', '.') }}%</span></p>
                            <p class="text-sm font-medium text-gray-700">Installment Period: <span class="font-semibold text-gray-800">{{ $loan->loan_months ?? '-' }} Months</span></p>
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
                            <span class="text-xs text-gray-400 font-medium block">Unpaid (Incl. Interest)</span>
                            <span class="text-sm font-bold text-gray-700">Rp. {{ number_format($totalUnpaid, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Total Interest</span>
                            <span class="text-sm font-bold text-blue-600">Rp. {{ number_format($totalInterest, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Interest Rate</span>
                            <span class="text-sm font-bold text-blue-600">{{ number_format($loan->loan_interest, 2, ',', '.') }}%</span>
                        </div>
                    </div>
                </div>
                </div>
                @if($loan->loan_status == 1)
                <div class="card-footer bg-white border-t border-gray-100 py-3 px-6 flex justify-end">
                    <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-150 ease-in-out" onclick="openRepayModal()">
                        <i class="fas fa-money-check-alt mr-2"></i> Early Repayment
                    </button>
                </div>
                @endif
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
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Interest Rate</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Interest Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Total Amount</th>
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
                            <td class="px-6 py-4 align-middle font-semibold text-gray-700">Rp. {{ number_format((float) $schedule->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-blue-600 font-medium">{{ $schedule->loan_interest_schedule ? number_format((float) $schedule->loan_interest_schedule * 100, 2, ',', '.') . '%' : '-' }}</td>
                            <td class="px-6 py-4 align-middle text-blue-600 font-medium">Rp. {{ number_format((float) ($schedule->loan_interest_sched_amount ?? 0), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle font-bold text-gray-800">Rp. {{ number_format((float) ($schedule->loan_total_sched_amount ?? 0), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-gray-600">Rp. {{ number_format((float) $schedule->remaining_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle text-green-600">Rp. {{ number_format((float) $schedule->paid_amount, 0, ',', '.') }}</td>
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
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                    <p class="font-medium text-gray-500">Installment payment schedule is empty.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($loan->schedules->isNotEmpty())
                <tfoot class="bg-gray-50/80 font-bold border-t-2 border-gray-200 text-gray-900 text-sm">
                    <tr>
                        <td class="px-6 py-4 align-middle" colspan="2">Total</td>
                        <td class="px-6 py-4 align-middle font-bold text-gray-900">Rp. {{ number_format((float) $loan->loan_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 align-middle text-gray-400 font-normal">-</td>
                        <td class="px-6 py-4 align-middle text-blue-600 font-bold">Rp. {{ number_format((float) $totalInterest, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 align-middle font-extrabold text-gray-900">Rp. {{ number_format((float) ($loan->loan_amount + $totalInterest), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 align-middle text-gray-400 font-normal">-</td>
                        <td class="px-6 py-4 align-middle text-green-600 font-bold">Rp. {{ number_format((float) $totalPaid, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 align-middle" colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Repay Modal -->
    <div id="repayModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pelunasan Pinjaman Dipercepat</h3>
                <div class="mt-2 px-4 py-3">
                    <p class="text-sm text-gray-500 mb-4">
                        Pilih metode pelunasan sisa pinjaman Anda:
                    </p>
                    <form id="repayForm" method="POST" action="{{ route('loans.repay', $loan) }}">
                        @csrf
                        <div class="mt-4 text-left border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                            <label class="inline-flex items-start w-full cursor-pointer">
                                <input type="radio" class="form-radio text-green-600 mt-1" name="with_interest" value="1" checked>
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Sertakan Sisa Bunga</span>
                                    <span class="block text-xs text-gray-500 mt-1">Total Bayar: <span class="font-bold text-gray-800">Rp. {{ number_format($totalUnpaid, 0, ',', '.') }}</span></span>
                                </div>
                            </label>
                        </div>
                        <div class="mt-3 text-left border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                            <label class="inline-flex items-start w-full cursor-pointer">
                                <input type="radio" class="form-radio text-green-600 mt-1" name="with_interest" value="0">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Tanpa Bunga (Pokok Saja)</span>
                                    <span class="block text-xs text-gray-500 mt-1">Total Bayar: <span class="font-bold text-gray-800">Rp. {{ number_format($totalUnpaid - $loan->schedules->where('payment_status', 1)->sum('loan_interest_sched_amount'), 0, ',', '.') }}</span></span>
                                </div>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="items-center px-4 py-3 mt-4 flex justify-between gap-3 border-t border-gray-100 pt-4">
                    <button id="closeModalBtn" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md w-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Batal
                    </button>
                    <button type="submit" form="repayForm" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md w-full hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Proses Pelunasan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRepayModal() {
            document.getElementById('repayModal').classList.remove('hidden');
        }
        document.getElementById('closeModalBtn').addEventListener('click', function() {
            document.getElementById('repayModal').classList.add('hidden');
        });
    </script>
</x-admin-layout>
