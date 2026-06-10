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

    <div class="max-w-3xl mx-auto">
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
                        <label for="loan_amount" class="block text-sm font-medium text-gray-700 mb-2">Loan Amount (IDR) <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="loan_amount" id="loan_amount" value="{{ old('loan_amount') }}" class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm bg-gray-50" placeholder="0" required min="1" step="1">
                        </div>
                    </div>

                    <!-- Loan Date -->
                    <div class="mb-5">
                        <label for="loan_date" class="block text-sm font-medium text-gray-700 mb-2">Loan Date <span class="text-red-500">*</span></label>
                        <input type="date" name="loan_date" id="loan_date" value="{{ old('loan_date', now()->format('Y-m-d')) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm px-3 py-2 bg-gray-50" required>
                    </div>

                    <!-- Loan Description -->
                    <div class="mb-5">
                        <label for="loan_description" class="block text-sm font-medium text-gray-700 mb-2">Description / Notes</label>
                        <textarea name="loan_description" id="loan_description" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Enter loan details...">{{ old('loan_description') }}</textarea>
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
</x-admin-layout>
