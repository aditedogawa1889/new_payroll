<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

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
        }

        $employees = $query->get();

        $jobTitles = Employee::whereNotNull('job_title')->distinct()->pluck('job_title')->sort();
        $jobLevels = Employee::whereNotNull('job_level')->distinct()->pluck('job_level')->sort();
        $locations = Employee::whereNotNull('location_current_year')->distinct()->pluck('location_current_year')->sort();

        return view('admin.employees.index', compact('employees', 'jobTitles', 'jobLevels', 'locations'));
    }

    public function show(Employee $employee)
    {
        // Load all histories
        $employee->load(['terminations', 'promotions', 'demotions', 'mutations']);
        
        return view('admin.employees.show', compact('employee'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        // Simple store for manual entry if needed
        $validated = $request->validate([
            'emp_number' => 'required|unique:employees',
            'employee_id' => 'required',
            'employee_name' => 'required',
            'email' => 'required|email',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'employee_name' => 'required',
            'email' => 'required|email',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->update(['is_delete' => 1]);
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
