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
                                        <div class="mb-5 p-4 border border-gray-100 rounded-lg bg-gray-50/30 shadow-sm">
                                            <div class="flex justify-between items-center mb-2">
                                                <label class="block text-sm font-semibold text-gray-800">{{ $component->nama_component }}</label>
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-blue-50 text-blue-600 font-bold uppercase tracking-wider">{{ $component->component_parameter }}</span>
                                            </div>

                                            @if($component->component_parameter === 'general')
                                                <div class="relative">
                                                    <input type="number" step="any" min="0" name="components[{{ $component->id_component }}][value]" 
                                                           value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" 
                                                           placeholder="Nominal (e.g. 5000000)">
                                                </div>
                                            @elseif($component->component_parameter === 'percentage')
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Persentase (%)</label>
                                                        <input type="number" step="any" min="0" max="100" name="components[{{ $component->id_component }}][value]" 
                                                               value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" 
                                                               placeholder="e.g. 10">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Komponen Acuan (Basis)</label>
                                                        <div class="max-h-32 overflow-y-auto border border-gray-200 rounded-md p-2 bg-white flex flex-col space-y-1.5">
                                                            @php
                                                                $savedBases = $employeeComponents->has($component->id_component) ? ($employeeComponents[$component->id_component]->basis_components ?? []) : [];
                                                            @endphp
                                                            @foreach($components->where('id_type_component', 1) as $basis)
                                                                @if($basis->id_component !== $component->id_component)
                                                                    <label class="flex items-center text-xs text-gray-700 font-normal cursor-pointer select-none">
                                                                        <input type="checkbox" name="components[{{ $component->id_component }}][basis_components][]" value="{{ $basis->id_component }}"
                                                                               class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary mr-2"
                                                                               {{ in_array($basis->id_component, $savedBases) ? 'checked' : '' }}>
                                                                        {{ $basis->nama_component }}
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($component->component_parameter === 'custom')
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Variable Picker</label>
                                                    <div class="flex flex-wrap gap-1 mb-2">
                                                        @foreach($components as $var)
                                                            @if($var->id_component !== $component->id_component)
                                                                <button type="button" onclick="insertVariable('formula-{{ $component->id_component }}', '[{{ $var->nama_component }}]')"
                                                                        class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100 text-[10px] font-semibold px-2 py-1 rounded transition-colors duration-150 shadow-sm">
                                                                    + {{ $var->nama_component }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Math Helper</label>
                                                    <div class="flex flex-wrap gap-1 mb-3">
                                                        @foreach(['+', '-', '*', '/', '(', ')', '%'] as $op)
                                                            <button type="button" onclick="insertVariable('formula-{{ $component->id_component }}', ' {{ $op }} ')"
                                                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 text-[10px] font-bold px-2 py-1 rounded transition-colors duration-150 shadow-sm">
                                                                {{ $op }}
                                                            </button>
                                                        @endforeach
                                                    </div>

                                                    <textarea id="formula-{{ $component->id_component }}" name="components[{{ $component->id_component }}][custom_formula]"
                                                              class="w-full text-xs font-mono rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary transition-colors"
                                                              rows="3" placeholder="e.g. ([Gaji Pokok] * 10%) + 500000">{{ old('components.' . $component->id_component . '.custom_formula', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->custom_formula : '') }}</textarea>
                                                    <p class="text-gray-400 text-[10px] mt-1 font-medium">Klik pada pill variabel atau operasi di atas untuk memasukkan rumus.</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pengurangan -->
                                <div>
                                    <h4 class="text-md font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                                        <i class="fas fa-minus-circle text-orange-500 mr-2"></i> Pengurangan
                                    </h4>
                                    @foreach($components->where('id_type_component', 2) as $component)
                                        <div class="mb-5 p-4 border border-gray-100 rounded-lg bg-gray-50/30 shadow-sm">
                                            <div class="flex justify-between items-center mb-2">
                                                <label class="block text-sm font-semibold text-gray-800">{{ $component->nama_component }}</label>
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-orange-50 text-orange-600 font-bold uppercase tracking-wider">{{ $component->component_parameter }}</span>
                                            </div>

                                            @if($component->component_parameter === 'general')
                                                <div class="relative">
                                                    <input type="number" step="any" min="0" name="components[{{ $component->id_component }}][value]" 
                                                           value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" 
                                                           placeholder="Nominal (e.g. 500000)">
                                                </div>
                                            @elseif($component->component_parameter === 'percentage')
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Persentase (%)</label>
                                                        <input type="number" step="any" min="0" max="100" name="components[{{ $component->id_component }}][value]" 
                                                               value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors" 
                                                               placeholder="e.g. 10">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Komponen Acuan (Basis)</label>
                                                        <div class="max-h-32 overflow-y-auto border border-gray-200 rounded-md p-2 bg-white flex flex-col space-y-1.5">
                                                            @php
                                                                $savedBases = $employeeComponents->has($component->id_component) ? ($employeeComponents[$component->id_component]->basis_components ?? []) : [];
                                                            @endphp
                                                            @foreach($components->where('id_type_component', 2) as $basis)
                                                                @if($basis->id_component !== $component->id_component)
                                                                    <label class="flex items-center text-xs text-gray-700 font-normal cursor-pointer select-none">
                                                                        <input type="checkbox" name="components[{{ $component->id_component }}][basis_components][]" value="{{ $basis->id_component }}"
                                                                               class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary mr-2"
                                                                               {{ in_array($basis->id_component, $savedBases) ? 'checked' : '' }}>
                                                                        {{ $basis->nama_component }}
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($component->component_parameter === 'custom')
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Variable Picker</label>
                                                    <div class="flex flex-wrap gap-1 mb-2">
                                                        @foreach($components as $var)
                                                            @if($var->id_component !== $component->id_component)
                                                                <button type="button" onclick="insertVariable('formula-{{ $component->id_component }}', '[{{ $var->nama_component }}]')"
                                                                        class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100 text-[10px] font-semibold px-2 py-1 rounded transition-colors duration-150 shadow-sm">
                                                                    + {{ $var->nama_component }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Math Helper</label>
                                                    <div class="flex flex-wrap gap-1 mb-3">
                                                        @foreach(['+', '-', '*', '/', '(', ')', '%'] as $op)
                                                            <button type="button" onclick="insertVariable('formula-{{ $component->id_component }}', ' {{ $op }} ')"
                                                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 text-[10px] font-bold px-2 py-1 rounded transition-colors duration-150 shadow-sm">
                                                                {{ $op }}
                                                            </button>
                                                        @endforeach
                                                    </div>

                                                    <textarea id="formula-{{ $component->id_component }}" name="components[{{ $component->id_component }}][custom_formula]"
                                                              class="w-full text-xs font-mono rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary transition-colors"
                                                              rows="3" placeholder="e.g. ([Gaji Pokok] * 10%) + 500000">{{ old('components.' . $component->id_component . '.custom_formula', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->custom_formula : '') }}</textarea>
                                                    <p class="text-gray-400 text-[10px] mt-1 font-medium">Klik pada pill variabel atau operasi di atas untuk memasukkan rumus.</p>
                                                </div>
                                            @endif
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

    @push('scripts')
    <script>
        function insertVariable(textareaId, value) {
            const textarea = document.getElementById(textareaId);
            if (textarea) {
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;
                const before = text.substring(0, start);
                const after = text.substring(end, text.length);
                textarea.value = before + value + after;
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = start + value.length;
            }
        }
    </script>
    @endpush
</x-admin-layout>
