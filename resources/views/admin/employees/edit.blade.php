<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Edit Employee</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}" class="text-adminlte-primary hover:underline">Employees</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Edit</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl">
        <div class="admin-card">
            <form action="{{ route('employees.update', $employee) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Basic Information</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Employee ID</label>
                            <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="employee_name" value="{{ old('employee_name', $employee->employee_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm">
                                <option value="" {{ old('gender', $employee->gender) == '' ? 'selected' : '' }}>-- Select Gender --</option>
                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Join Date</label>
                            <input type="text" value="{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d/m/Y') : '-' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                            <input type="hidden" name="join_date" value="{{ old('join_date', $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <!-- Job & Financial Information -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold border-b pb-2 text-gray-700">Job & Personal Documents</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Job Title</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $employee->job_title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Job Level</label>
                            <input type="text" name="job_level" value="{{ old('job_level', $employee->job_level) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location_current_year" value="{{ old('location_current_year', $employee->location_current_year) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. KTP (National ID)</label>
                            <div class="mt-1 flex rounded-md shadow-sm relative">
                                <input type="text" id="ktp_display" value="{{ $employee->ktp ? str_repeat('•', strlen($employee->ktp)) : '-' }}" data-real-value="{{ $employee->ktp ?? '' }}" class="block w-full border-gray-300 rounded-l-md bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                                <input type="hidden" name="ktp" value="{{ old('ktp', $employee->ktp) }}">
                                <button type="button" onclick="toggleReveal('ktp_display', this)" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. NPWP (Tax ID)</label>
                            <div class="mt-1 flex rounded-md shadow-sm relative">
                                <input type="text" id="npwp_display" value="{{ $employee->npwp ? str_repeat('•', strlen($employee->npwp)) : '-' }}" data-real-value="{{ $employee->npwp ?? '' }}" class="block w-full border-gray-300 rounded-l-md bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                                <input type="hidden" name="npwp" value="{{ old('npwp', $employee->npwp) }}">
                                <button type="button" onclick="toggleReveal('npwp_display', this)" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">BPJS Kesehatan</label>
                                <div class="mt-1 flex rounded-md shadow-sm relative">
                                    <input type="text" id="bpjs_kesehatan_display" value="{{ $employee->bpjs_kesehatan ? str_repeat('•', strlen($employee->bpjs_kesehatan)) : '-' }}" data-real-value="{{ $employee->bpjs_kesehatan ?? '' }}" class="block w-full border-gray-300 rounded-l-md bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                                    <input type="hidden" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', $employee->bpjs_kesehatan) }}">
                                    <button type="button" onclick="toggleReveal('bpjs_kesehatan_display', this)" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100 focus:outline-none">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">BPJS Ketenagakerjaan</label>
                                <div class="mt-1 flex rounded-md shadow-sm relative">
                                    <input type="text" id="bpjs_ketenagakerjaan_display" value="{{ $employee->bpjs_ketenagakerjaan ? str_repeat('•', strlen($employee->bpjs_ketenagakerjaan)) : '-' }}" data-real-value="{{ $employee->bpjs_ketenagakerjaan ?? '' }}" class="block w-full border-gray-300 rounded-l-md bg-gray-100 cursor-not-allowed sm:text-sm" readonly>
                                    <input type="hidden" name="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan', $employee->bpjs_ketenagakerjaan) }}">
                                    <button type="button" onclick="toggleReveal('bpjs_ketenagakerjaan_display', this)" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100 focus:outline-none">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t pt-6">
                    <h4 class="text-lg font-bold border-b pb-2 text-gray-700 mb-6">Bank Account Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm" placeholder="e.g. BCA, Mandiri">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank Account Number</label>
                            <input type="text" name="bank_account" value="{{ old('bank_account', $employee->bank_account) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank Account Holder Name</label>
                            <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $employee->bank_account_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-adminlte-primary focus:border-adminlte-primary sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 border-t pt-6">
                    <a href="{{ route('employees.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                        Cancel
                    </a>
                    <button type="submit" class="bg-adminlte-primary text-white px-8 py-2.5 rounded-lg shadow hover:bg-[#3d6638] transition-all duration-200 font-bold">
                        Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

@push('scripts')
<script>
    function toggleReveal(id, btn) {
        const input = document.getElementById(id);
        const realValue = input.getAttribute('data-real-value');
        const icon = btn.querySelector('i');
        
        if (!realValue) return;

        if (icon.classList.contains('fa-eye')) {
            input.value = realValue;
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.value = '•'.repeat(realValue.length);
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
