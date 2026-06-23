<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Add Employee Loan</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('loans.index') }}" class="text-adminlte-primary hover:underline">Loans</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Create</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto" x-data="loanForm()">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left: Form Card -->
            <div class="lg:col-span-2">
                <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden">
                    <div class="card-header bg-white border-b border-gray-100 py-4 px-6 flex justify-between items-center">
                        <h3 class="card-title text-gray-700 font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-plus-circle text-green-600"></i>
                            Loan Input Form
                        </h3>
                    </div>
                    
                    <div class="card-body p-6">
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('loans.store') }}" method="POST">
                            @csrf
                            
                            <!-- Employee Selector -->
                            <div class="mb-5" x-data="{
                                open: false,
                                search: '',
                                selectedId: {{ json_encode(old('employee_id') ?? '') }},
                                selectedLabel: '',
                                employees: [
                                    @foreach($employees as $employee)
                                    {
                                        id: '{{ $employee->emp_number }}',
                                        text: {{ json_encode($employee->employee_id . ' - ' . $employee->employee_name . ' (' . $employee->job_title . ')') }}
                                    },
                                    @endforeach
                                ],
                                get filteredEmployees() {
                                    if (this.search === '') return this.employees;
                                    return this.employees.filter(emp => emp.text.toLowerCase().includes(this.search.toLowerCase()));
                                },
                                select(emp) {
                                    this.selectedId = emp.id;
                                    this.selectedLabel = emp.text;
                                    this.search = emp.text;
                                    this.open = false;
                                },
                                init() {
                                    let initial = this.employees.find(emp => emp.id == this.selectedId);
                                    if (initial) {
                                        this.selectedLabel = initial.text;
                                        this.search = initial.text;
                                    }
                                }
                            }" @click.outside="open = false" class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Employee <span class="text-red-500">*</span></label>
                                
                                <div class="relative">
                                    <!-- Hidden input for form submission -->
                                    <input type="hidden" name="employee_id" :value="selectedId" required>

                                    <!-- Search Input -->
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            placeholder="-- Select Employee --" 
                                            x-model="search" 
                                            @focus="open = true; if (selectedId) { search = '' }" 
                                            @blur="setTimeout(() => { if (!selectedId) { search = '' } else { search = selectedLabel } }, 200)"
                                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm px-3 py-2 bg-gray-50 pr-10"
                                            autocomplete="off"
                                        >
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>

                                    <!-- Dropdown Options -->
                                    <div 
                                        x-show="open" 
                                        x-transition
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                        style="display: none;"
                                    >
                                        <template x-for="emp in filteredEmployees" :key="emp.id">
                                            <div 
                                                @mousedown="select(emp)" 
                                                class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-green-50 hover:text-green-900 transition-colors"
                                                :class="selectedId == emp.id ? 'bg-green-100 text-green-950 font-semibold' : 'text-gray-900'"
                                            >
                                                <span class="block truncate" x-text="emp.text"></span>
                                                <span 
                                                    x-show="selectedId == emp.id"
                                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-green-600"
                                                >
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            </div>
                                        </template>
                                        <div x-show="filteredEmployees.length === 0" class="text-gray-500 text-center py-3 select-none">
                                            No employee found
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Loan Amount -->
                            <div class="mb-5">
                                <label for="loan_amount_display" class="block text-sm font-medium text-gray-700 mb-2">Loan Amount (IDR) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <!-- Hidden input to submit raw numeric value to backend -->
                                    <input type="hidden" name="loan_amount" :value="loanAmount">
                                    
                                    <!-- Visible text input showing thousands separator -->
                                    <input 
                                        type="text" 
                                        id="loan_amount_display" 
                                        x-model="loanAmountDisplay"
                                        @input="updateLoanAmount($event.target.value)"
                                        class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm bg-gray-50" 
                                        placeholder="0" 
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Loan Interest -->
                            <div class="mb-5">
                                <label for="loan_interest" class="block text-sm font-medium text-gray-700 mb-2">Loan Interest (%) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                    <input type="number" name="loan_interest" id="loan_interest" x-model.number="loanInterest" value="{{ old('loan_interest', 1) }}" class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm bg-gray-50" placeholder="1" required min="0" step="0.01">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Persentase bunga pinjaman per cicilan. Contoh: 1 = 1%</p>
                            </div>

                            <!-- Total Months -->
                            <div class="mb-5">
                                <label for="loan_months" class="block text-sm font-medium text-gray-700 mb-2">Installment Period (Months) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="number" name="loan_months" id="loan_months" x-model.number="loanMonths" value="{{ old('loan_months', 12) }}" class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm bg-gray-50" placeholder="12" required min="1" max="120" step="1">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Jumlah total bulan cicilan. Cicilan akan digenerate otomatis.</p>
                            </div>

                            <!-- Loan Date -->
                            <div class="mb-5">
                                <label for="loan_date" class="block text-sm font-medium text-gray-700 mb-2">Loan Date <span class="text-red-500">*</span></label>
                                <input type="date" name="loan_date" id="loan_date" x-model="loanDate" value="{{ old('loan_date', now()->format('Y-m-d')) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm px-3 py-2 bg-gray-50" required>
                            </div>

                            <!-- Loan Description -->
                            <div class="mb-5">
                                <label for="loan_description" class="block text-sm font-medium text-gray-700 mb-2">Description / Notes</label>
                                <textarea name="loan_description" id="loan_description" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Enter loan details...">{{ old('loan_description') }}</textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="mt-6 flex justify-end space-x-2 border-t border-gray-100 pt-5">
                                <a href="{{ route('loans.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 sm:text-sm transition-colors shadow-sm">
                                    <i class="fas fa-save mr-1.5"></i> Save Loan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Schedule Preview Card -->
            <div class="lg:col-span-3">
                <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden">
                    <div class="card-header bg-white border-b border-gray-100 py-4 px-6 flex justify-between items-center">
                        <h3 class="card-title text-gray-700 font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-green-600"></i>
                            Installment Schedule Preview
                        </h3>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full" :class="scheduleRows.length > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" x-text="scheduleRows.length + ' bulan'"></span>
                    </div>
                    <div class="card-body p-0 overflow-x-auto">
                        <!-- Summary Stats -->
                        <template x-if="scheduleRows.length > 0">
                            <div class="p-4 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-100">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Monthly Installment</span>
                                        <span class="text-sm font-bold text-gray-800" x-text="'Rp. ' + formatNumber(monthlyAmount)"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Monthly Interest</span>
                                        <span class="text-sm font-bold text-blue-600" x-text="'Rp. ' + formatNumber(monthlyInterest)"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Monthly Total</span>
                                        <span class="text-sm font-bold text-green-700" x-text="'Rp. ' + formatNumber(monthlyTotal)"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Total Interest</span>
                                        <span class="text-sm font-bold text-blue-600" x-text="'Rp. ' + formatNumber(totalInterestAll)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <table class="table table-hover table-striped w-full mb-0 text-xs">
                            <thead class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Month & Year</th>
                                    <th class="px-4 py-2.5 text-right font-semibold text-gray-700 uppercase tracking-wider">Installment</th>
                                    <th class="px-4 py-2.5 text-right font-semibold text-gray-700 uppercase tracking-wider">Interest</th>
                                    <th class="px-4 py-2.5 text-right font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-2.5 text-right font-semibold text-gray-700 uppercase tracking-wider">Remaining</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <template x-if="scheduleRows.length === 0">
                                    <tr>
                                        <td colspan="6" class="px-4 py-10 text-center text-gray-400 bg-gray-50/30">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-calculator text-3xl text-gray-300 mb-2"></i>
                                                <p class="font-medium text-sm">Fill in loan amount, interest, and months to preview the schedule.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="(row, idx) in scheduleRows" :key="idx">
                                    <tr class="hover:bg-gray-50/40 transition-colors border-b border-gray-100">
                                        <td class="px-4 py-2.5 align-middle" x-text="idx + 1"></td>
                                        <td class="px-4 py-2.5 align-middle font-medium text-gray-800" x-text="row.monthLabel"></td>
                                        <td class="px-4 py-2.5 align-middle text-right font-semibold text-gray-700" x-text="'Rp. ' + formatNumber(row.amount)"></td>
                                        <td class="px-4 py-2.5 align-middle text-right text-blue-600 font-medium" x-text="'Rp. ' + formatNumber(row.interestAmount)"></td>
                                        <td class="px-4 py-2.5 align-middle text-right font-bold text-gray-800" x-text="'Rp. ' + formatNumber(row.totalAmount)"></td>
                                        <td class="px-4 py-2.5 align-middle text-right text-gray-500" x-text="'Rp. ' + formatNumber(row.remaining)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function loanForm() {
            return {
                loanAmount: {{ old('loan_amount', 0) }},
                loanInterest: {{ old('loan_interest', 1) }},
                loanMonths: {{ old('loan_months', 12) }},
                loanDate: '{{ old('loan_date', now()->format('Y-m-d')) }}',
                loanAmountDisplay: '',

                init() {
                    if (this.loanAmount > 0) {
                        this.loanAmountDisplay = this.formatNumber(this.loanAmount);
                    }
                },

                updateLoanAmount(val) {
                    // Remove non-digit characters
                    let clean = val.replace(/\D/g, '');
                    let num = parseInt(clean) || 0;
                    
                    this.loanAmount = num;
                    this.loanAmountDisplay = num > 0 ? this.formatNumber(num) : '';
                },

                get interestRate() {
                    return (this.loanInterest || 0) / 100;
                },

                get monthlyAmount() {
                    if (!this.loanAmount || !this.loanMonths || this.loanMonths < 1) return 0;
                    return Math.round(this.loanAmount / this.loanMonths);
                },

                get monthlyInterest() {
                    return Math.round(this.monthlyAmount * this.interestRate * 100) / 100;
                },

                get monthlyTotal() {
                    return this.monthlyAmount + this.monthlyInterest;
                },

                get totalInterestAll() {
                    return Math.round(this.monthlyInterest * (this.loanMonths || 0) * 100) / 100;
                },

                get scheduleRows() {
                    if (!this.loanAmount || !this.loanMonths || this.loanMonths < 1 || !this.loanDate) return [];

                    const rows = [];
                    const months = parseInt(this.loanMonths) || 0;
                    const amount = parseFloat(this.loanAmount) || 0;
                    const monthly = Math.round(amount / months);
                    const rate = this.interestRate;
                    const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

                    // Parse start date and add 1 month
                    let startDate = new Date(this.loanDate);
                    startDate.setMonth(startDate.getMonth() + 1);

                    for (let i = 0; i < months; i++) {
                        let currentDate = new Date(startDate);
                        currentDate.setMonth(currentDate.getMonth() + i);

                        const m = currentDate.getMonth(); // 0-indexed
                        const y = currentDate.getFullYear();
                        
                        // Last month adjustment to avoid rounding issues
                        const installment = (i === months - 1) ? (amount - (monthly * (months - 1))) : monthly;
                        const interest = Math.round(installment * rate * 100) / 100;
                        const total = installment + interest;
                        let remaining = amount - (monthly * (i + 1));
                        if (remaining < 0 || i === months - 1) remaining = 0;

                        rows.push({
                            monthLabel: monthNames[m] + ' ' + y,
                            month: m + 1,
                            year: y,
                            amount: installment,
                            interestAmount: interest,
                            totalAmount: total,
                            remaining: remaining
                        });
                    }

                    return rows;
                },

                formatNumber(num) {
                    if (num === null || num === undefined || isNaN(num)) return '0';
                    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
            };
        }
    </script>
    @endpush
</x-admin-layout>

