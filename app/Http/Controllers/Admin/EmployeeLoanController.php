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
            'loan_date' => 'required|date',
            'loan_description' => 'nullable|string|max:1000',
        ]);

        $validated['loan_status'] = 1; // 1 = Aktif

        $loan = EmployeeLoan::create($validated);

        return redirect()->route('loans.show', $loan->loan_id)->with('success', 'Loan created successfully. Please download the Excel template and upload the installment schedule.');
    }

    public function show($loan_id)
    {
        $loan = EmployeeLoan::with(['employee', 'schedules' => function($q) {
            $q->orderBy('year_number')->orderBy('month_number');
        }])->findOrFail($loan_id);

        $totalPaid = $loan->schedules->sum('paid_amount');
        $totalUnpaid = $loan->schedules->where('payment_status', 1)->sum('amount');
        $remainingBalance = $loan->loan_amount - $totalPaid;

        return view('admin.loans.show', compact('loan', 'totalPaid', 'totalUnpaid', 'remainingBalance'));
    }

    public function downloadTemplate($loan_id)
    {
        $loan = EmployeeLoan::findOrFail($loan_id);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->setCellValue('A1', 'month_number');
        $sheet->setCellValue('B1', 'year_number');
        $sheet->setCellValue('C1', 'amount');
        $sheet->setCellValue('D1', 'remaining_amount');

        // Style headers
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        
        // Generate sample data: 12 months evenly split
        $approxMonths = 12;
        $monthlyAmount = round($loan->loan_amount / $approxMonths);
        
        $currentDate = now()->addMonth();
        for ($i = 1; $i <= $approxMonths; $i++) {
            $rowNum = $i + 1;
            $remaining = $loan->loan_amount - ($monthlyAmount * $i);
            if ($remaining < 0 || $i === $approxMonths) {
                $remaining = 0;
            }

            $sheet->setCellValue('A' . $rowNum, $currentDate->month);
            $sheet->setCellValue('B' . $rowNum, $currentDate->year);
            $sheet->setCellValue('C' . $rowNum, $monthlyAmount);
            $sheet->setCellValue('D' . $rowNum, $remaining);
            
            $currentDate->addMonth();
        }

        // Set column auto size
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'template_cicilan_pinjaman_' . $loan->loan_id . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importSchedule(Request $request, $loan_id)
    {
        $loan = EmployeeLoan::findOrFail($loan_id);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ], [
            'file.mimes' => 'File must be in Excel format (.xlsx or .xls)'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $dataRows = $sheet->toArray(null, true, true, true);

            // Expect headers on row 1
            $headerRow = array_map(function($h) {
                return strtolower(trim($h));
            }, $dataRows[1] ?? []);

            $expectedHeaders = ['month_number', 'year_number', 'amount', 'remaining_amount'];
            foreach ($expectedHeaders as $expected) {
                if (!in_array($expected, $headerRow)) {
                    return redirect()->back()->with('error', 'Excel header format is invalid. Must contain: month_number, year_number, amount, remaining_amount.');
                }
            }

            $headerMapping = array_flip($headerRow);
            $schedules = [];

            for ($rowIdx = 2; $rowIdx <= count($dataRows); $rowIdx++) {
                $row = $dataRows[$rowIdx];
                
                if (empty(array_filter($row))) {
                    continue;
                }

                $monthKey = $headerMapping['month_number'];
                $yearKey = $headerMapping['year_number'];
                $amountKey = $headerMapping['amount'];
                $remainingKey = $headerMapping['remaining_amount'];

                $month = intval($row[$monthKey] ?? 0);
                $year = intval($row[$yearKey] ?? 0);
                $amount = floatval($row[$amountKey] ?? 0);
                $remaining = floatval($row[$remainingKey] ?? 0);

                if ($month < 1 || $month > 12 || $year < 1000 || $amount < 0 || $remaining < 0) {
                    return redirect()->back()->with('error', 'Row ' . $rowIdx . ' contains invalid data. Make sure month is (1-12), year is (4 digits), and amount/remaining is non-negative.');
                }

                $schedules[] = [
                    'month_number' => $month,
                    'year_number' => $year,
                    'amount' => $amount,
                    'remaining_amount' => $remaining,
                    'paid_amount' => 0,
                    'payment_status' => 1, // 1 = Aktif / Unpaid
                ];
            }

            if (empty($schedules)) {
                return redirect()->back()->with('error', 'The Excel file does not have any installment data rows.');
            }

            \DB::beginTransaction();
            try {
                // Delete existing schedules
                $loan->schedules()->delete();

                // Create new schedules
                foreach ($schedules as $sched) {
                    $loan->schedules()->create($sched);
                }

                \DB::commit();
                return redirect()->route('loans.show', $loan->loan_id)->with('success', 'Installment schedule imported successfully from Excel.');
            } catch (\Exception $ex) {
                \DB::rollBack();
                return redirect()->back()->with('error', 'Failed to save data to the database: ' . $ex->getMessage());
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to read the Excel file: ' . $e->getMessage());
        }
    }
}
