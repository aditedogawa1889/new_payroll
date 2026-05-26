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
        $query = Employee::query();

        // Check if any filter is filled
        $hasFilter = $request->filled('nik') || 
                     $request->filled('name') || 
                     $request->filled('job_title') || 
                     $request->filled('job_level') || 
                     $request->filled('location') || 
                     $request->filled('status');

        if ($request->filled('nik')) {
            $query->where('employee_id', $request->nik);
        }

        if ($request->filled('name')) {
            $query->where('employee_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('job_title')) {
            $query->where('job_title', $request->job_title);
        }

        if ($request->filled('job_level')) {
            $query->where('job_level', $request->job_level);
        }

        if ($request->filled('location')) {
            $query->where('location_current_year', $request->location);
        }

        if ($request->filled('status')) {
            if ($request->status === 'Past') {
                $query->whereNotNull('termination_date');
            } elseif ($request->status === 'Active') {
                $query->whereNull('termination_date');
            }
        } else {
            // Default to showing only active employees in Salary Settings
            $query->whereNull('termination_date');
        }

        // If no filter/search criteria is applied, show only where is_set_salary = 0
        if (!$hasFilter) {
            $query->where('is_set_salary', 0);
        }

        $employees = $query->get();

        $jobTitles = Employee::whereNotNull('job_title')->distinct()->pluck('job_title')->sort();
        $jobLevels = Employee::whereNotNull('job_level')->distinct()->pluck('job_level')->sort();
        $locations = Employee::whereNotNull('location_current_year')->distinct()->pluck('location_current_year')->sort();

        return view('admin.salary_settings.index', compact('employees', 'jobTitles', 'jobLevels', 'locations'));
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

        $employee->update(['is_set_salary' => 1]);

        return redirect()->route('salary-settings.index')->with('success', 'Salary settings updated for ' . $employee->employee_name);
    }
}
