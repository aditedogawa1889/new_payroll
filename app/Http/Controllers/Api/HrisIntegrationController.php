<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHrisIntegrationRequest;
use App\Models\EmployeeIntegration;
use App\Models\MasterIntegrationType;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeOvertime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HrisIntegrationController extends Controller
{
    public function store(StoreHrisIntegrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $validated['is_processed'] = 0;
        $validated['created_by'] = $request->user() ? $request->user()->name : 'API';
        $validated['updated_by'] = $request->user() ? $request->user()->name : 'API';

        $integration = EmployeeIntegration::create($validated);

        return response()->json([
            'message' => 'Integration stored',
            'integration_id' => $integration->id_integration,
        ], 201);
    }

    public function getTypes(): JsonResponse
    {
        $types = MasterIntegrationType::where('is_active', 1)->get();
        return response()->json($types);
    }
    public function storeAttendance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,emp_number',
            'period_st' => 'required|date',
            'period_en' => 'required|date|after_or_equal:period_st',
            'payroll_date' => 'nullable|date',
            'attendance_days' => 'required|numeric|min:0',
        ]);

        $attendance = EmployeeAttendance::create($validated);

        return response()->json([
            'message' => 'Attendance summary stored',
            'id_attendance' => $attendance->id_attendance,
        ], 201);
    }

    public function storeOvertime(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,emp_number',
            'period_ot_st' => 'required|date',
            'period_ot_en' => 'required|date|after_or_equal:period_ot_st',
            'payroll_ot_date' => 'nullable|date',
            'overtime_hours' => 'required|numeric|min:0',
            'overtime_meals' => 'required|numeric|min:0',
        ]);

        $overtime = EmployeeOvertime::create($validated);

        return response()->json([
            'message' => 'Overtime summary stored',
            'id_overtime' => $overtime->id_overtime,
        ], 201);
    }
}
