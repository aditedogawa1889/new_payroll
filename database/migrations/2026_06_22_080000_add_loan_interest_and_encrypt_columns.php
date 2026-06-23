<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Database\CustomEncryptor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add loan_interest to employee_loans
        Schema::table('employee_loans', function (Blueprint $table) {
            $table->decimal('loan_interest', 18, 2)->default(1)->after('loan_amount');
        });

        // 2. Add new columns to employee_loans_schedule
        Schema::table('employee_loans_schedule', function (Blueprint $table) {
            $table->text('loan_interest_schedule')->nullable()->after('amount');
            $table->text('loan_interest_sched_amount')->nullable()->after('loan_interest_schedule');
            $table->text('loan_total_sched_amount')->nullable()->after('loan_interest_sched_amount');
        });

        // 3. Encrypt existing loan_amount data in employee_loans
        // Change loan_amount column from decimal to text for encrypted storage
        $existingLoans = DB::table('employee_loans')->select('loan_id', 'loan_amount')->get();

        Schema::table('employee_loans', function (Blueprint $table) {
            $table->text('loan_amount')->change();
        });

        foreach ($existingLoans as $loan) {
            if (!is_null($loan->loan_amount)) {
                DB::table('employee_loans')
                    ->where('loan_id', $loan->loan_id)
                    ->update([
                        'loan_amount' => CustomEncryptor::deterministicEncrypt($loan->loan_amount)
                    ]);
            }
        }

        // 4. Encrypt existing schedule data
        // First, read existing data before altering column types
        $existingSchedules = DB::table('employee_loans_schedule')
            ->select('loan_schedule_id', 'amount', 'remaining_amount', 'paid_amount')
            ->get();

        // Change columns from decimal to text
        Schema::table('employee_loans_schedule', function (Blueprint $table) {
            $table->text('amount')->change();
            $table->text('remaining_amount')->change();
            $table->text('paid_amount')->change();
        });

        // Encrypt existing data
        foreach ($existingSchedules as $schedule) {
            $updateData = [];

            if (!is_null($schedule->amount)) {
                $amount = (float) $schedule->amount;
                $updateData['amount'] = CustomEncryptor::deterministicEncrypt($schedule->amount);

                // Also compute interest fields for existing data (using default interest 1% = 0.01)
                $loanInterestSchedule = 0.01; // default 1%
                $interestAmount = $amount * $loanInterestSchedule;
                $totalAmount = $amount + $interestAmount;

                $updateData['loan_interest_schedule'] = CustomEncryptor::deterministicEncrypt((string) $loanInterestSchedule);
                $updateData['loan_interest_sched_amount'] = CustomEncryptor::deterministicEncrypt((string) round($interestAmount, 2));
                $updateData['loan_total_sched_amount'] = CustomEncryptor::deterministicEncrypt((string) round($totalAmount, 2));
            }

            if (!is_null($schedule->remaining_amount)) {
                $updateData['remaining_amount'] = CustomEncryptor::deterministicEncrypt($schedule->remaining_amount);
            }

            if (!is_null($schedule->paid_amount)) {
                $updateData['paid_amount'] = CustomEncryptor::deterministicEncrypt($schedule->paid_amount);
            }

            if (!empty($updateData)) {
                DB::table('employee_loans_schedule')
                    ->where('loan_schedule_id', $schedule->loan_schedule_id)
                    ->update($updateData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Decrypt existing data back before reverting column types

        // 1. Decrypt employee_loans_schedule
        $schedules = DB::table('employee_loans_schedule')
            ->select('loan_schedule_id', 'amount', 'remaining_amount', 'paid_amount')
            ->get();

        foreach ($schedules as $schedule) {
            $updateData = [];
            if (!is_null($schedule->amount)) {
                $updateData['amount'] = CustomEncryptor::deterministicDecrypt($schedule->amount);
            }
            if (!is_null($schedule->remaining_amount)) {
                $updateData['remaining_amount'] = CustomEncryptor::deterministicDecrypt($schedule->remaining_amount);
            }
            if (!is_null($schedule->paid_amount)) {
                $updateData['paid_amount'] = CustomEncryptor::deterministicDecrypt($schedule->paid_amount);
            }
            if (!empty($updateData)) {
                DB::table('employee_loans_schedule')
                    ->where('loan_schedule_id', $schedule->loan_schedule_id)
                    ->update($updateData);
            }
        }

        // Revert column types
        Schema::table('employee_loans_schedule', function (Blueprint $table) {
            $table->decimal('amount', 18, 0)->change();
            $table->decimal('remaining_amount', 18, 0)->change();
            $table->decimal('paid_amount', 18, 0)->default(0)->change();
        });

        // Drop new columns
        Schema::table('employee_loans_schedule', function (Blueprint $table) {
            $table->dropColumn(['loan_interest_schedule', 'loan_interest_sched_amount', 'loan_total_sched_amount']);
        });

        // 2. Decrypt employee_loans
        $loans = DB::table('employee_loans')->select('loan_id', 'loan_amount')->get();

        foreach ($loans as $loan) {
            if (!is_null($loan->loan_amount)) {
                DB::table('employee_loans')
                    ->where('loan_id', $loan->loan_id)
                    ->update([
                        'loan_amount' => CustomEncryptor::deterministicDecrypt($loan->loan_amount)
                    ]);
            }
        }

        Schema::table('employee_loans', function (Blueprint $table) {
            $table->decimal('loan_amount', 18, 0)->change();
        });

        Schema::table('employee_loans', function (Blueprint $table) {
            $table->dropColumn('loan_interest');
        });
    }
};
