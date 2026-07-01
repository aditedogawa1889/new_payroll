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
    public function calculate(Request $request, Employee $employee)
    {
        try {
            $month = $request->query('month');
            $year = $request->query('year');
            $result = $this->calculationEngine->calculate($employee->emp_number, $month, $year);
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
                'message' => 'A system error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process and record payment of payroll and corresponding loan schedules.
     *
     * @param Request $request
     * @param string $emp_number
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request, Employee $employee)
    {
        try {
            $month = $request->input('month');
            $year = $request->input('year');

            if (is_null($month) || is_null($year)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Month and year must be selected.'
                ], 422);
            }

            // Find unpaid schedules for this month and year
            $schedules = \App\Models\EmployeeLoanSchedule::whereHas('loan', function($q) use ($employee) {
                    $q->where('employee_id', $employee->emp_number)
                      ->where('loan_status', 1); // Only active loans
                })
                ->where('month_number', $month)
                ->where('year_number', $year)
                ->where('payment_status', 1) // 1 = Aktif / Unpaid
                ->get();

            if ($schedules->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active loan installments need to be paid in this period.'
                ], 422);
            }

            \DB::beginTransaction();
            foreach ($schedules as $sched) {
                $sched->update([
                    'paid_amount' => $sched->amount,
                    'payment_date' => now(),
                    'payment_status' => 2, // 2 = Lunas
                    'updated_by' => auth()->user()->name ?? 'system'
                ]);

                // Check if all schedules under this loan are fully paid (payment_status = 2)
                $loan = $sched->loan;
                $hasUnpaid = $loan->schedules()->where('payment_status', 1)->exists();
                if (!$hasUnpaid) {
                    $loan->update([
                        'loan_status' => 2, // Lunas
                        'loan_updated_at' => now(),
                        'loan_updated_by' => auth()->user()->name ?? 'system'
                    ]);
                }
            }
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payroll payment and loan installment deduction successfully processed.'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
