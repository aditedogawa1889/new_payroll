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
        $employee = Employee::where('emp_number', $emp_number)->firstOrFail();
        $components = MdComponentPayroll::where('is_active', true)->get();

        foreach ($components as $component) {
            $value = $request->input('components.' . $component->id_component);
            
            if (!is_null($value)) {
                EmployeePayrollComponent::updateOrCreate(
                    [
                        'emp_number' => $emp_number,
                        'id_component' => $component->id_component
                    ],
                    [
                        'value_component' => $value,
                        'is_active' => true,
                        'updated_by' => auth()->user()->name ?? 'system'
                    ]
                );
            } else {
                // If value is null, maybe deactivate or ignore? We'll set is_active false.
                EmployeePayrollComponent::where('emp_number', $emp_number)
                    ->where('id_component', $component->id_component)
                    ->update(['is_active' => false, 'updated_by' => auth()->user()->name ?? 'system']);
            }
        }

        return redirect()->route('salary-settings.index')->with('success', 'Salary settings updated for ' . $employee->employee_name);
    }
}
