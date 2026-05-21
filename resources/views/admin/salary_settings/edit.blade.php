<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Manage Salary Components</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Employee Info -->
        <div class="col-span-1">
            <div class="card elevation-2 border-0 rounded-lg">
                <div class="card-header bg-white border-b border-gray-100 py-4">
                    <h3 class="card-title text-gray-700 font-semibold">Employee Details</h3>
                </div>
                <div class="card-body p-4 text-sm text-gray-700">
                    <p class="mb-2"><strong class="block text-gray-500 text-xs uppercase">Name</strong> <span class="font-medium text-base">{{ $employee->employee_name }}</span></p>
                    <p class="mb-2"><strong class="block text-gray-500 text-xs uppercase">NIK</strong> {{ $employee->employee_id }}</p>
                    <p class="mb-2"><strong class="block text-gray-500 text-xs uppercase">Job Title</strong> {{ $employee->job_title }}</p>
                    <p class="mb-2"><strong class="block text-gray-500 text-xs uppercase">Level</strong> {{ $employee->job_level }}</p>
                </div>
            </div>
        </div>

        <!-- Components Form -->
        <div class="col-span-1 md:col-span-2">
            <div class="card elevation-2 border-0 rounded-lg">
                <div class="card-header bg-white border-b border-gray-100 py-4">
                    <h3 class="card-title text-gray-700 font-semibold">Component Values</h3>
                </div>
                <form action="{{ route('salary-settings.update', $employee->emp_number) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-6">
                        @if ($components->isEmpty())
                            <div class="text-center text-gray-500 py-4">
                                <p>No active master salary components available.</p>
                                <a href="{{ route('salary-components.index') }}" class="text-adminlte-primary hover:underline mt-2 inline-block">Manage Master Components</a>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Penambahan -->
                                <div>
                                    <h4 class="text-md font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                                        <i class="fas fa-plus-circle text-blue-500 mr-2"></i> Penambahan
                                    </h4>
                                    @foreach($components->where('id_type_component', 1) as $component)
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $component->nama_component }}</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" step="any" name="components[{{ $component->id_component }}]" 
                                                       value="{{ $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '' }}" 
                                                       class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pengurangan -->
                                <div>
                                    <h4 class="text-md font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                                        <i class="fas fa-minus-circle text-orange-500 mr-2"></i> Pengurangan
                                    </h4>
                                    @foreach($components->where('id_type_component', 2) as $component)
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $component->nama_component }}</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" step="any" name="components[{{ $component->id_component }}]" 
                                                       value="{{ $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '' }}" 
                                                       class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer bg-gray-50/50 py-3 px-6 flex justify-end space-x-3 rounded-b-lg">
                        <a href="{{ route('salary-settings.index') }}" class="btn btn-default btn-sm px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors shadow-sm font-medium">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-md shadow-sm font-medium transition-colors hover:bg-adminlte-primary-dark">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
