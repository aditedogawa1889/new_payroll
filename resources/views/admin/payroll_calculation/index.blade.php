<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Payroll Calculation</h2>
    </x-slot>

    <div x-data="{
        openModal: false,
        loading: false,
        errorMessage: '',
        calcData: null,
        formatCurrency(val) {
            return 'Rp. ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(val);
        },
        calculatePayroll(empNumber) {
            this.loading = true;
            this.errorMessage = '';
            this.openModal = true;
            this.calcData = null;
            
            fetch(`/admin/payroll/calculate/${empNumber}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(json => {
                this.calcData = json.data;
                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                this.errorMessage = err.message || 'Terjadi kesalahan saat menghitung gaji.';
            });
        }
    }">
        <!-- Employee List Card -->
        <div class="card elevation-2 border-0 rounded-lg bg-white shadow-sm overflow-hidden">
            <div class="card-header bg-white border-b border-gray-100 py-4 px-6 flex justify-between items-center">
                <h3 class="card-title text-gray-700 font-semibold text-lg flex items-center gap-2">
                    <i class="fas fa-calculator text-green-600"></i>
                    Hitung Gaji Karyawan
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-striped w-full mb-0 text-sm">
                    <thead class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-3.5 text-left font-semibold text-gray-700 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3.5 text-center font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divider-y divider-gray-100">
                        @forelse($employees as $index => $employee)
                            <tr class="hover:bg-gray-50/40 transition-colors border-b border-gray-100">
                                <td class="px-6 py-4 align-middle">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 align-middle font-medium">{{ $employee->employee_id }}</td>
                                <td class="px-6 py-4 align-middle font-medium text-gray-800">{{ $employee->employee_name }}</td>
                                <td class="px-6 py-4 align-middle text-gray-600">{{ $employee->job_title }}</td>
                                <td class="px-6 py-4 align-middle text-gray-600">{{ $employee->job_level }}</td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <button @click="calculatePayroll('{{ $employee->emp_number }}')" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold tracking-wide text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all duration-150 transform hover:-translate-y-0.5 active:translate-y-0">
                                        <i class="fas fa-play text-[10px]"></i>
                                        Hitung Gaji
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                                        <p class="font-medium text-gray-500">Tidak ada data karyawan aktif.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Premium Calculation Breakdown Modal -->
        <div x-show="openModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div @click.away="if (!loading) openModal = false" 
                 class="bg-white/95 rounded-2xl shadow-2xl border border-gray-100 max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden transform transition-all duration-300"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 translate-y-4"
                 x-transition:enter-end="scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 translate-y-0"
                 x-transition:leave-end="scale-95 translate-y-4">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-green-600"></i>
                        Rincian Perhitungan Gaji (Payslip)
                    </h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto flex-grow">
                    <!-- Loading State -->
                    <div x-show="loading" class="flex flex-col items-center justify-center py-16">
                        <div class="relative w-16 h-16 mb-4">
                            <div class="absolute inset-0 rounded-full border-4 border-green-100"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-green-600 border-t-transparent animate-spin"></div>
                        </div>
                        <h4 class="text-gray-700 font-semibold text-lg">Mengevaluasi Gaji...</h4>
                        <p class="text-gray-400 text-sm mt-1">Menganalisis dependensi variabel dan memproses rumus matematika...</p>
                    </div>

                    <!-- Error State -->
                    <div x-show="errorMessage" class="p-5 bg-red-50 border border-red-100 rounded-xl text-red-700 flex items-start gap-4">
                        <div class="p-2 bg-red-100 rounded-lg text-red-600">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-800 text-md">Gagal Menghitung Gaji</h4>
                            <p class="text-sm mt-1" x-text="errorMessage"></p>
                        </div>
                    </div>

                    <!-- Data State -->
                    <div x-show="calcData && !loading" class="space-y-6">
                        <!-- Employee Info Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50/50 border border-gray-100 rounded-xl">
                            <div>
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Nama Karyawan</span>
                                <span class="font-bold text-gray-800 text-base" x-text="calcData?.employee?.employee_name"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">NIK</span>
                                <span class="font-medium text-gray-700 text-base" x-text="calcData?.employee?.employee_id"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Jabatan / Level</span>
                                <span class="font-medium text-gray-700 text-base">
                                    <span x-text="calcData?.employee?.job_title"></span>
                                    <span class="text-gray-400" x-text="'(' + calcData?.employee?.job_level + ')'"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Topological Sort Order Section -->
                        <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                <i class="fas fa-sitemap text-green-600"></i>
                                Urutan Evaluasi Komponen (Topological Sort Order)
                            </h4>
                            <div class="flex flex-wrap items-center gap-2">
                                <template x-for="(comp, idx) in calcData?.components" :key="comp.id_component">
                                    <div class="flex items-center">
                                        <div class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-xs font-medium text-gray-700 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="{
                                                'bg-green-500': comp.type === 'Penambahan',
                                                'bg-red-500': comp.type === 'Pengurangan'
                                            }"></span>
                                            <span x-text="comp.nama_component"></span>
                                        </div>
                                        <template x-if="idx < calcData.components.length - 1">
                                            <i class="fas fa-chevron-right text-gray-300 mx-2 text-xs"></i>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Earnings and Deductions side-by-side -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Earnings -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-bold text-gray-700 border-b border-gray-100 pb-2 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                    Penambahan (Earnings)
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="comp in calcData?.components.filter(c => c.type === 'Penambahan')" :key="comp.id_component">
                                        <div class="p-3.5 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-green-200 transition-colors flex flex-col justify-between">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h5 class="font-semibold text-gray-800 text-sm" x-text="comp.nama_component"></h5>
                                                    <!-- Component Type Badge -->
                                                    <span class="inline-block px-2 py-0.5 mt-1 rounded text-[10px] font-bold"
                                                          :class="{
                                                              'bg-blue-50 text-blue-600 border border-blue-100': comp.parameter === 'general',
                                                              'bg-purple-50 text-purple-600 border border-purple-100': comp.parameter === 'percentage',
                                                              'bg-orange-50 text-orange-600 border border-orange-100': comp.parameter === 'custom'
                                                          }"
                                                          x-text="comp.parameter.toUpperCase()"></span>
                                                </div>
                                                <span class="font-bold text-green-600 text-sm" x-text="formatCurrency(comp.evaluated_value)"></span>
                                            </div>
                                            <!-- Additional parameter specific info -->
                                            <div class="mt-2 text-xs text-gray-500 bg-gray-50/50 p-2 rounded-lg" x-show="comp.parameter !== 'general'">
                                                <!-- Percentage details -->
                                                <template x-if="comp.parameter === 'percentage'">
                                                    <div>
                                                        <div class="font-medium text-gray-600" x-text="'Rate: ' + comp.input_value + '%'"></div>
                                                        <div class="mt-1 flex flex-wrap gap-1 items-center">
                                                            <span class="text-[10px] text-gray-400">Basis:</span>
                                                            <template x-for="b in comp.basis_components">
                                                                <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 rounded text-[10px]" x-text="b"></span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                                <!-- Custom details -->
                                                <template x-if="comp.parameter === 'custom'">
                                                    <div class="space-y-1">
                                                        <div><span class="text-[10px] text-gray-400">Formula:</span> <code class="bg-gray-100 px-1 py-0.5 rounded font-mono text-[10px] text-orange-700" x-text="comp.custom_formula"></code></div>
                                                        <div><span class="text-[10px] text-gray-400">Substitusi:</span> <code class="bg-gray-100 px-1 py-0.5 rounded font-mono text-[10px] text-gray-600" x-text="comp.expanded_formula"></code></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="p-3.5 bg-green-50/30 border border-green-100/50 rounded-xl flex justify-between items-center">
                                        <span class="font-bold text-gray-700 text-sm">Total Penambahan</span>
                                        <span class="font-bold text-green-700 text-base" x-text="formatCurrency(calcData?.total_earnings)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Deductions -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-bold text-gray-700 border-b border-gray-100 pb-2 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                    Pengurangan (Deductions)
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="comp in calcData?.components.filter(c => c.type === 'Pengurangan')" :key="comp.id_component">
                                        <div class="p-3.5 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-red-200 transition-colors flex flex-col justify-between">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h5 class="font-semibold text-gray-800 text-sm" x-text="comp.nama_component"></h5>
                                                    <!-- Component Type Badge -->
                                                    <span class="inline-block px-2 py-0.5 mt-1 rounded text-[10px] font-bold"
                                                          :class="{
                                                              'bg-blue-50 text-blue-600 border border-blue-100': comp.parameter === 'general',
                                                              'bg-purple-50 text-purple-600 border border-purple-100': comp.parameter === 'percentage',
                                                              'bg-orange-50 text-orange-600 border border-orange-100': comp.parameter === 'custom'
                                                          }"
                                                          x-text="comp.parameter.toUpperCase()"></span>
                                                </div>
                                                <span class="font-bold text-red-600 text-sm" x-text="formatCurrency(comp.evaluated_value)"></span>
                                            </div>
                                            <!-- Additional parameter specific info -->
                                            <div class="mt-2 text-xs text-gray-500 bg-gray-50/50 p-2 rounded-lg" x-show="comp.parameter !== 'general'">
                                                <!-- Percentage details -->
                                                <template x-if="comp.parameter === 'percentage'">
                                                    <div>
                                                        <div class="font-medium text-gray-600" x-text="'Rate: ' + comp.input_value + '%'"></div>
                                                        <div class="mt-1 flex flex-wrap gap-1 items-center">
                                                            <span class="text-[10px] text-gray-400">Basis:</span>
                                                            <template x-for="b in comp.basis_components">
                                                                <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 rounded text-[10px]" x-text="b"></span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                                <!-- Custom details -->
                                                <template x-if="comp.parameter === 'custom'">
                                                    <div class="space-y-1">
                                                        <div><span class="text-[10px] text-gray-400">Formula:</span> <code class="bg-gray-100 px-1 py-0.5 rounded font-mono text-[10px] text-orange-700" x-text="comp.custom_formula"></code></div>
                                                        <div><span class="text-[10px] text-gray-400">Substitusi:</span> <code class="bg-gray-100 px-1 py-0.5 rounded font-mono text-[10px] text-gray-600" x-text="comp.expanded_formula"></code></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="p-3.5 bg-red-50/30 border border-red-100/50 rounded-xl flex justify-between items-center">
                                        <span class="font-bold text-gray-700 text-sm">Total Pengurangan</span>
                                        <span class="font-bold text-red-700 text-base" x-text="formatCurrency(calcData?.total_deductions)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Net Salary Take Home Pay Summary -->
                        <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3.5">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm text-white">
                                    <i class="fas fa-wallet text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-white/90">Take Home Pay (Gaji Bersih)</h4>
                                    <p class="text-xs text-white/75">Gaji bersih yang diterima karyawan setelah seluruh penambahan dan pengurangan dihitung.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold tracking-tight" x-text="formatCurrency(calcData?.net_salary)"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button @click="openModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold rounded-lg text-sm transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
