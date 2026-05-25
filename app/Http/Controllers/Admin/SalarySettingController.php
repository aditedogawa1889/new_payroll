<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\MdComponentPayroll;
use App\Models\EmployeePayrollComponent;
use Illuminate\Http\Request;

class SalarySettingController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::whereNull('termination_date')->get();
        return view('admin.salary_settings.index', compact('employees'));
    }

    public function edit($emp_number)
    {
        $employee = Employee::where('emp_number', $emp_number)->firstOrFail();
        $components = MdComponentPayroll::where('is_active', true)->with('type')->get();
        
        $employeeComponents = EmployeePayrollComponent::where('emp_number', $emp_number)
            ->where('is_active', true)
            ->get()
            ->keyBy('id_component');

        return view('admin.salary_settings.edit', compact('employee', 'components', 'employeeComponents'));
    }

    public function update(Request $request, $emp_number)
    {
        $request->validate([
            'components' => 'array',
            'components.*.value' => 'nullable|numeric|min:0',
            'components.*.basis_components' => 'nullable|array',
            'components.*.custom_formula' => 'nullable|string|max:1000',
        ], [
            'components.*.value.min' => 'Nilai komponen tidak boleh minus.'
        ]);

        $employee = Employee::where('emp_number', $emp_number)->firstOrFail();
        $components = MdComponentPayroll::where('is_active', true)->get();

        foreach ($components as $component) {
            $input = $request->input('components.' . $component->id_component, []);
            
            $val = null;
            $basis = null;
            $formula = null;

            if ($component->component_parameter === 'general') {
                $val = isset($input['value']) && $input['value'] !== '' ? $input['value'] : null;
            } elseif ($component->component_parameter === 'percentage') {
                $val = isset($input['value']) && $input['value'] !== '' ? $input['value'] : null;
                $basis = $input['basis_components'] ?? [];
            } elseif ($component->component_parameter === 'custom') {
                $formula = $input['custom_formula'] ?? null;
            }

            $hasSetting = !is_null($val) || !empty($basis) || !empty($formula);

            if ($hasSetting) {
                EmployeePayrollComponent::updateOrCreate(
                    [
                        'emp_number' => $emp_number,
                        'id_component' => $component->id_component
                    ],
                    [
                        'value_component' => $val,
                        'basis_components' => $basis,
                        'custom_formula' => $formula,
                        'is_active' => true,
                        'updated_by' => auth()->user()->name ?? 'system'
                    ]
                );
            } else {
                EmployeePayrollComponent::where('emp_number', $emp_number)
                    ->where('id_component', $component->id_component)
                    ->update(['is_active' => false, 'updated_by' => auth()->user()->name ?? 'system']);
            }
        }

        return redirect()->route('salary-settings.index')->with('success', 'Salary settings updated for ' . $employee->employee_name);
    }
}
