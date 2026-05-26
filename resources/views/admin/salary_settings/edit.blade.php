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
                <form id="salary-settings-form" action="{{ route('salary-settings.update', $employee->emp_number) }}" method="POST">
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
                                                    <input type="text" name="components[{{ $component->id_component }}][value]" 
                                                           value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                           data-id="{{ $component->id_component }}"
                                                           data-name="{{ $component->nama_component }}"
                                                           data-type="general"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors thousands-separator" 
                                                           placeholder="Nominal (e.g. 5.000.000)">
                                                </div>
                                            @elseif($component->component_parameter === 'percentage')
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Persentase (%)</label>
                                                        <input type="number" step="any" min="0" max="100" name="components[{{ $component->id_component }}][value]" 
                                                               value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                               data-id="{{ $component->id_component }}"
                                                               data-name="{{ $component->nama_component }}"
                                                               data-type="percentage"
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
                                                                               class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary mr-2 basis-checkbox"
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
                                                              data-id="{{ $component->id_component }}"
                                                              data-name="{{ $component->nama_component }}"
                                                              data-type="custom"
                                                              class="w-full text-xs font-mono rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary transition-colors"
                                                              rows="3" placeholder="e.g. ([Gaji Pokok] * 10%) + 500000">{{ old('components.' . $component->id_component . '.custom_formula', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->custom_formula : '') }}</textarea>
                                                    <p class="text-gray-400 text-[10px] mt-1 font-medium">Klik pada pill variabel atau operasi di atas untuk memasukkan rumus.</p>

                                                    <!-- Readonly Calculation Result -->
                                                    <div class="mt-3">
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Hasil Kalkulasi Rumus</label>
                                                        <input type="text" id="calc-result-{{ $component->id_component }}" readonly
                                                               class="w-full rounded-md border-gray-200 bg-gray-100/70 shadow-sm text-gray-700 sm:text-sm font-semibold select-none cursor-not-allowed calc-result-field"
                                                               value="0">
                                                    </div>
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
                                                    <input type="text" name="components[{{ $component->id_component }}][value]" 
                                                           value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                           data-id="{{ $component->id_component }}"
                                                           data-name="{{ $component->nama_component }}"
                                                           data-type="general"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary sm:text-sm transition-colors thousands-separator" 
                                                           placeholder="Nominal (e.g. 500.000)">
                                                </div>
                                            @elseif($component->component_parameter === 'percentage')
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Persentase (%)</label>
                                                        <input type="number" step="any" min="0" max="100" name="components[{{ $component->id_component }}][value]" 
                                                               value="{{ old('components.' . $component->id_component . '.value', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->value_component : '') }}" 
                                                               data-id="{{ $component->id_component }}"
                                                               data-name="{{ $component->nama_component }}"
                                                               data-type="percentage"
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
                                                                               class="rounded border-gray-300 text-adminlte-primary focus:ring-adminlte-primary mr-2 basis-checkbox"
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
                                                              data-id="{{ $component->id_component }}"
                                                              data-name="{{ $component->nama_component }}"
                                                              data-type="custom"
                                                              class="w-full text-xs font-mono rounded-md border-gray-300 shadow-sm focus:border-adminlte-primary focus:ring-adminlte-primary transition-colors"
                                                              rows="3" placeholder="e.g. ([Gaji Pokok] * 10%) + 500000">{{ old('components.' . $component->id_component . '.custom_formula', $employeeComponents->has($component->id_component) ? $employeeComponents[$component->id_component]->custom_formula : '') }}</textarea>
                                                    <p class="text-gray-400 text-[10px] mt-1 font-medium">Klik pada pill variabel atau operasi di atas untuk memasukkan rumus.</p>

                                                    <!-- Readonly Calculation Result -->
                                                    <div class="mt-3">
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Hasil Kalkulasi Rumus</label>
                                                        <input type="text" id="calc-result-{{ $component->id_component }}" readonly
                                                               class="w-full rounded-md border-gray-200 bg-gray-100/70 shadow-sm text-gray-700 sm:text-sm font-semibold select-none cursor-not-allowed calc-result-field"
                                                               value="0">
                                                    </div>
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
        // Shunting-Yard Math Parser matching backend PHP MathEvaluator
        class MathEvaluator {
            evaluate(expression) {
                expression = expression.trim();
                if (expression === '') {
                    return 0.0;
                }

                if (!/^[0-9+\-*\/%().\s]+$/.test(expression)) {
                    throw new Error("Formula contains invalid characters");
                }

                const tokens = this.tokenize(expression);
                if (tokens.length === 0) {
                    return 0.0;
                }

                const rpn = this.parseToRpn(tokens);
                return this.evaluateRpn(rpn);
            }

            tokenize(expression) {
                const matches = expression.match(/[0-9]+(?:\.[0-9]+)?|[+\-*\/%()]/g);
                return matches || [];
            }

            parseToRpn(tokens) {
                const outputQueue = [];
                const operatorStack = [];
                const operators = {
                    '+': { precedence: 2, associativity: 'left' },
                    '-': { precedence: 2, associativity: 'left' },
                    '*': { precedence: 3, associativity: 'left' },
                    '/': { precedence: 3, associativity: 'left' },
                    '%': { precedence: 3, associativity: 'left' },
                    'u-': { precedence: 4, associativity: 'right' },
                    'u+': { precedence: 4, associativity: 'right' }
                };

                let prevToken = null;

                for (let i = 0; i < tokens.length; i++) {
                    const token = tokens[i];
                    if (!isNaN(parseFloat(token)) && isFinite(token)) {
                        outputQueue.push(parseFloat(token));
                    } else if (token === '+' || token === '-') {
                        const isUnary = (prevToken === null || prevToken === '(' || operators[prevToken] !== undefined);
                        const op = isUnary ? 'u' + token : token;

                        while (operatorStack.length > 0) {
                            const top = operatorStack[operatorStack.length - 1];
                            if (top === '(') {
                                break;
                            }

                            const op1 = operators[op];
                            const op2 = operators[top];

                            if ((op1.associativity === 'left' && op1.precedence <= op2.precedence) ||
                                (op1.associativity === 'right' && op1.precedence < op2.precedence)) {
                                outputQueue.push(operatorStack.pop());
                            } else {
                                break;
                            }
                        }
                        operatorStack.push(op);
                    } else if (token === '*' || token === '/' || token === '%') {
                        const op = token;
                        while (operatorStack.length > 0) {
                            const top = operatorStack[operatorStack.length - 1];
                            if (top === '(') {
                                break;
                            }

                            const op1 = operators[op];
                            const op2 = operators[top];

                            if ((op1.associativity === 'left' && op1.precedence <= op2.precedence) ||
                                (op1.associativity === 'right' && op1.precedence < op2.precedence)) {
                                outputQueue.push(operatorStack.pop());
                            } else {
                                break;
                            }
                        }
                        operatorStack.push(op);
                    } else if (token === '(') {
                        operatorStack.push(token);
                    } else if (token === ')') {
                        let matched = false;
                        while (operatorStack.length > 0) {
                            const top = operatorStack.pop();
                            if (top === '(') {
                                matched = true;
                                break;
                            }
                            outputQueue.push(top);
                        }
                        if (!matched) {
                            throw new Error("Mismatched parentheses");
                        }
                    } else {
                        throw new Error("Invalid token in formula: " + token);
                    }

                    prevToken = token;
                }

                while (operatorStack.length > 0) {
                    const top = operatorStack.pop();
                    if (top === '(') {
                        throw new Error("Mismatched parentheses");
                    }
                    outputQueue.push(top);
                }

                return outputQueue;
            }

            evaluateRpn(rpn) {
                const stack = [];

                for (let i = 0; i < rpn.length; i++) {
                    const token = rpn[i];
                    if (typeof token === 'number') {
                        stack.push(token);
                    } else {
                        if (token === 'u-') {
                            if (stack.length === 0) {
                                throw new Error("Invalid unary minus");
                            }
                            const val = stack.pop();
                            stack.push(-val);
                        } else if (token === 'u+') {
                            if (stack.length === 0) {
                                throw new Error("Invalid unary plus");
                            }
                        } else {
                            if (stack.length < 2) {
                                throw new Error("Invalid binary expression");
                            }
                            const b = stack.pop();
                            const a = stack.pop();

                            switch (token) {
                                case '+':
                                    stack.push(a + b);
                                    break;
                                case '-':
                                    stack.push(a - b);
                                    break;
                                case '*':
                                    stack.push(a * b);
                                    break;
                                case '/':
                                    if (b === 0) {
                                        throw new Error("Division by zero");
                                    }
                                    stack.push(a / b);
                                    break;
                                case '%':
                                    if (b === 0) {
                                        throw new Error("Modulo by zero");
                                    }
                                    stack.push(a % b);
                                    break;
                                default:
                                    throw new Error("Unknown operator: " + token);
                            }
                        }
                    }
                }

                if (stack.length !== 1) {
                    throw new Error("Invalid expression");
                }

                return stack.pop();
            }
        }

        const mathEvaluator = new MathEvaluator();

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
                
                // Trigger input event to run calculations
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        function formatRupiah(num) {
            if (num === null || num === undefined || isNaN(num) || !isFinite(num)) {
                return '0';
            }
            const rounded = Math.round((num + Number.EPSILON) * 100) / 100;
            const parts = rounded.toString().split('.');
            let integerPart = parts[0];
            let decimalPart = parts[1];
            
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            
            if (decimalPart) {
                return integerPart + ',' + decimalPart;
            }
            return integerPart;
        }

        function formatInput(e) {
            const input = e.target;
            let value = input.value;
            
            let clean = value.replace(/[^\d]/g, '');
            let formatted = clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            
            let selectionStart = input.selectionStart;
            let charactersBeforeCursor = value.substring(0, selectionStart).replace(/[^\d]/g, '').length;
            
            input.value = formatted;
            
            let newCursorPos = 0;
            let digitCount = 0;
            for (let i = 0; i < formatted.length; i++) {
                if (formatted[i] !== '.') {
                    digitCount++;
                }
                if (digitCount <= charactersBeforeCursor) {
                    newCursorPos = i + 1;
                } else {
                    break;
                }
            }
            input.setSelectionRange(newCursorPos, newCursorPos);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const componentsMap = new Map();
            const nameToIdMap = new Map();

            // First scan and initialize components mapping
            function scanComponents() {
                componentsMap.clear();
                nameToIdMap.clear();

                // General components
                document.querySelectorAll('input[data-type="general"]').forEach(el => {
                    const id = el.getAttribute('data-id');
                    const name = el.getAttribute('data-name');
                    componentsMap.set(id, {
                        id: id,
                        name: name,
                        type: 'general',
                        element: el,
                        getValue: () => {
                            const raw = el.value.replace(/\./g, '');
                            const val = parseFloat(raw);
                            return isNaN(val) ? 0.0 : val;
                        }
                    });
                    nameToIdMap.set(name.trim().toLowerCase(), id);
                });

                // Percentage components
                document.querySelectorAll('input[data-type="percentage"]').forEach(el => {
                    const id = el.getAttribute('data-id');
                    const name = el.getAttribute('data-name');
                    componentsMap.set(id, {
                        id: id,
                        name: name,
                        type: 'percentage',
                        element: el,
                        getValue: () => {
                            const val = parseFloat(el.value);
                            return isNaN(val) ? 0.0 : val;
                        },
                        getBasisIds: () => {
                            const checked = [];
                            document.querySelectorAll(`input[name="components[${id}][basis_components][]"]:checked`).forEach(chk => {
                                checked.push(chk.value);
                            });
                            return checked;
                        }
                    });
                    nameToIdMap.set(name.trim().toLowerCase(), id);
                });

                // Custom components
                document.querySelectorAll('textarea[data-type="custom"]').forEach(el => {
                    const id = el.getAttribute('data-id');
                    const name = el.getAttribute('data-name');
                    componentsMap.set(id, {
                        id: id,
                        name: name,
                        type: 'custom',
                        element: el,
                        getFormula: () => el.value,
                        resultElement: document.getElementById(`calc-result-${id}`)
                    });
                    nameToIdMap.set(name.trim().toLowerCase(), id);
                });
            }

            function evaluate(id, path, memo) {
                if (path.has(id)) {
                    throw new Error("Circular dependency!");
                }
                if (memo[id] !== undefined) {
                    return memo[id];
                }

                const comp = componentsMap.get(id);
                if (!comp) {
                    return 0.0;
                }

                path.add(id);

                let val = 0.0;
                try {
                    if (comp.type === 'general') {
                        val = comp.getValue();
                    } else if (comp.type === 'percentage') {
                        const percentage = comp.getValue();
                        const basisIds = comp.getBasisIds();
                        let basisSum = 0.0;
                        basisIds.forEach(basisId => {
                            basisSum += evaluate(basisId, path, memo);
                        });
                        val = basisSum * (percentage / 100.0);
                    } else if (comp.type === 'custom') {
                        const formula = comp.getFormula();
                        const regex = /\[([^\]]+)\]/g;
                        let expandedFormula = formula;
                        let match;
                        const replacements = [];

                        while ((match = regex.exec(formula)) !== null) {
                            const varName = match[1];
                            const varNameClean = varName.trim().toLowerCase();
                            const refId = nameToIdMap.get(varNameClean);
                            let refVal = 0.0;
                            if (refId) {
                                refVal = evaluate(refId, path, memo);
                            }
                            replacements.push({
                                placeholder: match[0],
                                value: refVal
                            });
                        }

                        // Sort by length desc
                        replacements.sort((a, b) => b.placeholder.length - a.placeholder.length);

                        replacements.forEach(rep => {
                            expandedFormula = expandedFormula.replaceAll(rep.placeholder, rep.value.toString());
                        });

                        val = mathEvaluator.evaluate(expandedFormula);
                    }
                } catch (e) {
                    path.delete(id);
                    throw e;
                }

                path.delete(id);
                memo[id] = val;
                return val;
            }

            function updateCalculations() {
                scanComponents();
                const memo = {};
                
                document.querySelectorAll('textarea[data-type="custom"]').forEach(textarea => {
                    const id = textarea.getAttribute('data-id');
                    const resultInput = document.getElementById(`calc-result-${id}`);
                    if (!resultInput) return;

                    const path = new Set();
                    try {
                        const val = evaluate(id, path, memo);
                        resultInput.value = formatRupiah(val);
                        resultInput.classList.remove('text-red-500', 'border-red-500');
                        resultInput.classList.add('text-gray-700');
                    } catch (err) {
                        resultInput.value = "Error: " + err.message;
                        resultInput.classList.remove('text-gray-700');
                        resultInput.classList.add('text-red-500', 'border-red-500');
                    }
                });
            }

            // Initial formatting of inputs from database
            document.querySelectorAll('.thousands-separator').forEach(input => {
                let rawValue = input.value;
                if (rawValue) {
                    let parsed = parseFloat(rawValue);
                    if (!isNaN(parsed)) {
                        let rounded = Math.round(parsed);
                        input.value = rounded.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }
                }
            });

            // Event Listeners for inputs
            document.querySelectorAll('.thousands-separator').forEach(input => {
                input.addEventListener('input', formatInput);
                input.addEventListener('input', updateCalculations);
            });

            document.querySelectorAll('input[data-type="percentage"]').forEach(input => {
                input.addEventListener('input', updateCalculations);
            });

            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateCalculations);
            });

            document.querySelectorAll('textarea[data-type="custom"]').forEach(textarea => {
                textarea.addEventListener('input', updateCalculations);
            });

            // Remove formatting before form submission
            const form = document.getElementById('salary-settings-form');
            if (form) {
                form.addEventListener('submit', function() {
                    document.querySelectorAll('.thousands-separator').forEach(input => {
                        input.value = input.value.replace(/\./g, '');
                    });
                });
            }

            // Perform initial calculation on page load
            updateCalculations();
        });
    </script>
    @endpush
</x-admin-layout>
