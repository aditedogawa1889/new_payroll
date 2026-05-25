<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\PayrollCalculationEngine;
use Illuminate\Http\Request;

class PayrollCalculationController extends Controller
{
    protected $calculationEngine;

    public function __construct(PayrollCalculationEngine $calculationEngine)
    {
        $this->calculationEngine = $calculationEngine;
    }

    /**
     * Display a listing of active employees for payroll calculation.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $employees = Employee::whereNull('termination_date')->get();
        return view('admin.payroll_calculation.index', compact('employees'));
    }

    /**
     * Calculate and return detailed payroll figures for an employee.
     *
     * @param Request $request
     * @param string $emp_number
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request, $emp_number)
    {
        try {
            $result = $this->calculationEngine->calculate($emp_number);
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
