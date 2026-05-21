<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Employee Profile & History</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}" class="text-adminlte-primary hover:underline">Employees</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">History</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile info -->
        <div class="lg:col-span-1">
            <div class="admin-card border-t-4 border-adminlte-primary">
                <div class="admin-card-body text-center p-6">
                    <div class="mb-4">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-adminlte-primary/10 text-adminlte-primary text-4xl font-bold">
                            {{ strtoupper(substr($employee->employee_name, 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $employee->employee_name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $employee->job_title }}</p>
                    
                    <ul class="text-left divide-y divide-gray-100 text-sm">
                        <li class="py-2 flex justify-between">
                            <span class="font-semibold">Emp Number:</span>
                            <span>{{ $employee->emp_number }}</span>
                        </li>
                        <li class="py-2 flex justify-between">
                            <span class="font-semibold">Employee ID:</span>
                            <span>{{ $employee->employee_id }}</span>
                        </li>
                        <li class="py-2 flex justify-between">
                            <span class="font-semibold">Join Date:</span>
                            <span>{{ $employee->join_date }}</span>
                        </li>
                        <li class="py-2 flex justify-between">
                            <span class="font-semibold">Email:</span>
                            <span>{{ $employee->email }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- History tabs -->
        <div class="lg:col-span-2">
            <div class="admin-card">
                <div class="admin-card-header p-0 border-b border-gray-200">
                    <ul class="flex overflow-x-auto text-sm font-medium text-center text-gray-500" id="historyTabs">
                        <li class="mr-2">
                            <a href="#promotion" class="inline-block p-4 border-b-2 border-adminlte-primary text-adminlte-primary rounded-t-lg active">Promotion</a>
                        </li>
                        <li class="mr-2">
                            <a href="#mutation" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">Mutation</a>
                        </li>
                        <li class="mr-2">
                            <a href="#demotion" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">Demotion</a>
                        </li>
                        <li class="mr-2">
                            <a href="#termination" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">Termination</a>
                        </li>
                    </ul>
                </div>
                <div class="admin-card-body p-6">
                    <!-- Promotion History -->
                    <div id="promotion" class="tab-content">
                        <h4 class="font-semibold mb-4 text-adminlte-primary"><i class="fas fa-arrow-up mr-2"></i> Promotion Records</h4>
                        @if($employee->promotions->isEmpty())
                            <p class="text-gray-500 italic text-sm">No promotion records found.</p>
                        @else
                            <div class="relative pl-6 border-l-2 border-gray-100 space-y-6">
                                @foreach($employee->promotions as $promo)
                                <div class="relative">
                                    <div class="absolute -left-[1.625rem] w-4 h-4 rounded-full bg-adminlte-success border-2 border-white mt-1"></div>
                                    <div class="text-xs text-gray-400 font-semibold mb-1">{{ \Carbon\Carbon::parse($promo->effective_date)->format('d M Y') }}</div>
                                    <p class="text-sm">Promoted from <span class="font-bold text-gray-700">{{ $promo->previous_job_title_name }}</span> to <span class="font-bold text-adminlte-success">{{ $promo->new_job_title_name }}</span></p>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Mutation History (Hidden by default) -->
                    <div id="mutation" class="tab-content hidden">
                        <h4 class="font-semibold mb-4 text-adminlte-info"><i class="fas fa-exchange-alt mr-2"></i> Mutation Records</h4>
                        @if($employee->mutations->isEmpty())
                            <p class="text-gray-500 italic text-sm">No mutation records found.</p>
                        @else
                            <div class="relative pl-6 border-l-2 border-gray-100 space-y-6">
                                @foreach($employee->mutations as $mut)
                                <div class="relative">
                                    <div class="absolute -left-[1.625rem] w-4 h-4 rounded-full bg-adminlte-info border-2 border-white mt-1"></div>
                                    <div class="text-xs text-gray-400 font-semibold mb-1">{{ \Carbon\Carbon::parse($mut->effective_date)->format('d M Y') }}</div>
                                    <p class="text-sm">Mutated from <span class="font-bold text-gray-700">{{ $mut->previous_location_name }}</span> to <span class="font-bold text-adminlte-info">{{ $mut->new_location_name }}</span></p>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Demotion History (Hidden by default) -->
                    <div id="demotion" class="tab-content hidden">
                        <h4 class="font-semibold mb-4 text-adminlte-warning"><i class="fas fa-arrow-down mr-2"></i> Demotion Records</h4>
                        @if($employee->demotions->isEmpty())
                            <p class="text-gray-500 italic text-sm">No demotion records found.</p>
                        @else
                            <div class="relative pl-6 border-l-2 border-gray-100 space-y-6">
                                @foreach($employee->demotions as $demo)
                                <div class="relative">
                                    <div class="absolute -left-[1.625rem] w-4 h-4 rounded-full bg-adminlte-warning border-2 border-white mt-1"></div>
                                    <div class="text-xs text-gray-400 font-semibold mb-1">{{ \Carbon\Carbon::parse($demo->effective_date)->format('d M Y') }}</div>
                                    <p class="text-sm">Demoted from <span class="font-bold text-gray-700">{{ $demo->previous_job_title_name }}</span> to <span class="font-bold text-adminlte-warning">{{ $demo->new_job_title_name }}</span></p>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Termination History (Hidden by default) -->
                    <div id="termination" class="tab-content hidden">
                        <h4 class="font-semibold mb-4 text-adminlte-danger"><i class="fas fa-user-slash mr-2"></i> Termination Record</h4>
                        @if($employee->terminations->isEmpty())
                            <p class="text-gray-500 italic text-sm">No termination record found.</p>
                        @else
                            @foreach($employee->terminations as $term)
                            <div class="p-4 bg-red-50 rounded border border-red-100">
                                <div class="text-xs text-gray-400 font-semibold mb-1">{{ \Carbon\Carbon::parse($term->termination_date)->format('d M Y') }}</div>
                                <p class="text-sm font-bold text-red-700 mb-1">Reason: {{ $term->reason }}</p>
                                <p class="text-xs text-gray-500">Processed by: {{ $term->created_by }}</p>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#historyTabs a');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('href').substring(1);

                    // Remove active styles from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-adminlte-primary', 'text-adminlte-primary', 'active');
                        t.classList.add('border-transparent');
                    });

                    // Add active styles to clicked tab
                    this.classList.remove('border-transparent');
                    this.classList.add('border-adminlte-primary', 'text-adminlte-primary', 'active');

                    // Hide all contents
                    contents.forEach(c => c.classList.add('hidden'));

                    // Show target content
                    document.getElementById(target).classList.remove('hidden');
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>
