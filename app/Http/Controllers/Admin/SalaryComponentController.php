<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MdComponentPayroll;
use App\Models\MdTypeKomponen;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    public function index()
    {
        $components = MdComponentPayroll::with('type')->get();
        return view('admin.salary_components.index', compact('components'));
    }

    public function create()
    {
        $types = MdTypeKomponen::where('is_active', true)->get();
        return view('admin.salary_components.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_component' => 'required|string|max:255',
            'id_type_component' => 'required|exists:md_type_komponen,id_type_component',
            'component_parameter' => 'required|in:general,percentage,custom',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->user()->name ?? 'system';

        MdComponentPayroll::create($validated);

        return redirect()->route('salary-components.index')->with('success', 'Component created successfully.');
    }

    public function edit(MdComponentPayroll $salary_component)
    {
        $types = MdTypeKomponen::where('is_active', true)->get();
        return view('admin.salary_components.edit', compact('salary_component', 'types'));
    }

    public function update(Request $request, MdComponentPayroll $salary_component)
    {
        $validated = $request->validate([
            'nama_component' => 'required|string|max:255',
            'id_type_component' => 'required|exists:md_type_komponen,id_type_component',
            'component_parameter' => 'required|in:general,percentage,custom',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->user()->name ?? 'system';

        $salary_component->update($validated);

        return redirect()->route('salary-components.index')->with('success', 'Component updated successfully.');
    }

    public function destroy(MdComponentPayroll $salary_component)
    {
        $salary_component->delete();
        return redirect()->route('salary-components.index')->with('success', 'Component deleted successfully.');
    }
}
