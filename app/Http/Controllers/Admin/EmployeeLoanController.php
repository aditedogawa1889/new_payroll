<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLoan;
use App\Models\EmployeeLoanSchedule;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployeeLoanController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeLoan::with('employee');

        if ($request->filled('name')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('employee_name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('nik')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('employee_id', $request->nik);
            });
        }

        if ($request->filled('status')) {
            $query->where('loan_status', $request->status);
        }

        $loans = $query->get();

        return view('admin.loans.index', compact('loans'));
    }

    public function create()
    {
        $employees = Employee::whereNull('termination_date')->orderBy('employee_name')->get();
        return view('admin.loans.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,emp_number',
            'loan_amount' => 'required|numeric|min:1',
            'loan_interest' => 'required|numeric|min:0',
            'loan_months' => 'required|integer|min:1|max:120',
            'loan_date' => 'required|date',
            'loan_description' => 'nullable|string|max:1000',
        ]);

        $validated['loan_status'] = 1; // 1 = Aktif

        \DB::beginTransaction();
        try {
            $loan = EmployeeLoan::create($validated);

            // Auto-generate installment schedule
            $loanAmount = (float) $validated['loan_amount'];
            $loanMonths = (int) $validated['loan_months'];
            $interestRate = (float) $validated['loan_interest'] / 100; // Convert % to decimal
            $monthlyAmount = round($loanAmount / $loanMonths);

            $startDate = \Carbon\Carbon::parse($validated['loan_date'])->addMonth();

            for ($i = 0; $i < $loanMonths; $i++) {
                $currentDate = $startDate->copy()->addMonths($i);

                // Last month adjustment to avoid rounding issues
                $installment = ($i === $loanMonths - 1) ? ($loanAmount - ($monthlyAmount * ($loanMonths - 1))) : $monthlyAmount;
                $interestAmount = round($installment * $interestRate, 2);
                $totalAmount = round($installment + $interestAmount, 2);
                $remaining = $loanAmount - ($monthlyAmount * ($i + 1));
                if ($remaining < 0 || $i === $loanMonths - 1) {
                    $remaining = 0;
                }

                $loan->schedules()->create([
                    'month_number' => $currentDate->month,
                    'year_number' => $currentDate->year,
                    'amount' => $installment,
                    'loan_interest_schedule' => $interestRate,
                    'loan_interest_sched_amount' => $interestAmount,
                    'loan_total_sched_amount' => $totalAmount,
                    'remaining_amount' => $remaining,
                    'paid_amount' => 0,
                    'payment_status' => 1, // 1 = Unpaid
                ]);
            }

            \DB::commit();
            return redirect()->route('loans.show', $loan->loan_id)->with('success', 'Loan created successfully with ' . $loanMonths . ' installment schedules generated automatically.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create loan: ' . $e->getMessage());
        }
    }

    public function show($loan_id)
    {
        $loan = EmployeeLoan::with(['employee', 'schedules' => function($q) {
            $q->orderBy('year_number')->orderBy('month_number');
        }])->findOrFail($loan_id);

        $totalPaid = $loan->schedules->sum(function ($s) {
            return (float) $s->paid_amount;
        });
        $totalUnpaid = $loan->schedules->where('payment_status', 1)->sum(function ($s) {
            return (float) $s->loan_total_sched_amount;
        });
        $totalInterest = $loan->schedules->sum(function ($s) {
            return (float) $s->loan_interest_sched_amount;
        });
        $remainingBalance = (float) $loan->loan_amount - $totalPaid;

        return view('admin.loans.show', compact('loan', 'totalPaid', 'totalUnpaid', 'totalInterest', 'remainingBalance'));
    }
}
