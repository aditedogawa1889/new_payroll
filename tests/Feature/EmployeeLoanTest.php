<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeLoan;
use App\Models\EmployeeLoanSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeLoanTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'name' => 'Admin Test User',
        ]);
        
        $this->employee = Employee::create([
            'emp_number' => 1234,
            'employee_id' => 'EMP-1234',
            'employee_name' => 'Test Employee',
            'email' => 'employee.test@example.com',
        ]);
    }

    /**
     * Test creating a loan automatically generates the schedules.
     */
    public function test_create_loan_auto_generates_schedules(): void
    {
        $response = $this->actingAs($this->user)->post(route('loans.store'), [
            'employee_id' => $this->employee->emp_number,
            'loan_amount' => 10000000,
            'loan_interest' => 1.5, // 1.5%
            'loan_months' => 10,
            'loan_date' => '2026-06-22',
            'loan_description' => 'Test loan description',
        ]);

        // Should redirect to loans show page
        $loan = EmployeeLoan::first();
        $this->assertNotNull($loan);
        $this->assertEquals(10, $loan->loan_months);
        $location = $response->headers->get('Location');
        $prefix = url('/admin/loans') . '/';
        $this->assertStringStartsWith($prefix, $location);
        $encryptedKey = substr($location, strlen($prefix));
        $decryptedId = \Illuminate\Support\Facades\Crypt::decryptString($encryptedKey);
        $this->assertEquals($loan->loan_id, $decryptedId);

        // Total generated schedules should match loan_months
        $schedules = EmployeeLoanSchedule::where('loan_id', $loan->loan_id)->get();
        $this->assertCount(10, $schedules);

        // Verification of calculations:
        // Monthly amount: 10,000,000 / 10 = 1,000,000
        // Interest: 1.5% of 1,000,000 = 15,000
        // Total: 1,015,000
        foreach ($schedules as $index => $schedule) {
            $this->assertEquals(1000000, $schedule->amount);
            $this->assertEquals(0.015, $schedule->loan_interest_schedule);
            $this->assertEquals(15000, $schedule->loan_interest_sched_amount);
            $this->assertEquals(1015000, $schedule->loan_total_sched_amount);
            
            // Remaining balance checking
            $expectedRemaining = 10000000 - (1000000 * ($index + 1));
            $this->assertEquals($expectedRemaining, $schedule->remaining_amount);
        }
    }

    /**
     * Test loan detail view handles calculated totals correctly.
     */
    public function test_loan_detail_view_shows_correct_calculations(): void
    {
        $loan = EmployeeLoan::create([
            'employee_id' => $this->employee->emp_number,
            'loan_amount' => 5000000,
            'loan_interest' => 1, // 1%
            'loan_months' => 5,
            'loan_date' => '2026-06-22',
            'loan_status' => 1,
        ]);

        // Manually generate 5 schedules
        for ($i = 0; $i < 5; $i++) {
            $installment = 1000000;
            $interest = 10000;
            $total = 1010000;
            $remaining = 5000000 - ($installment * ($i + 1));

            $loan->schedules()->create([
                'month_number' => 7 + $i,
                'year_number' => 2026,
                'amount' => $installment,
                'loan_interest_schedule' => 0.01,
                'loan_interest_sched_amount' => $interest,
                'loan_total_sched_amount' => $total,
                'remaining_amount' => $remaining,
                'paid_amount' => 0,
                'payment_status' => 1,
            ]);
        }

        $response = $this->actingAs($this->user)->get(route('loans.show', $loan));
        $response->assertStatus(200);

        // Verify variables passed to the view
        $response->assertViewHas('totalPaid', 0.0);
        $response->assertViewHas('totalUnpaid', 5050000.0); // 5 * 1010000
        $response->assertViewHas('totalInterest', 50000.0); // 5 * 10000
        $response->assertViewHas('remainingBalance', 5000000.0);
    }

    /**
     * Test next loan installment schedule integration with Koperasi salary setting component.
     */
    public function test_koperasi_component_displays_next_installment_amount(): void
    {
        // 1. Seed parameters
        \DB::table('md_param_componen')->insertOrIgnore([
            ['id_param_component' => 1, 'nama_param_component' => 'general'],
            ['id_param_component' => 2, 'nama_param_component' => 'percentage'],
            ['id_param_component' => 3, 'nama_param_component' => 'custom'],
        ]);

        $deductionType = \App\Models\MdTypeKomponen::create([
            'nama_type_component' => 'Pengurangan',
            'is_active' => true,
        ]);

        // Create component "Potongan Koperasi"
        $koperasiComponent = \App\Models\MdComponentPayroll::create([
            'nama_component' => 'Potongan Koperasi',
            'id_type_component' => $deductionType->id_type_component,
            'component_parameter' => 'general',
            'is_active' => true,
        ]);

        // Create an active loan with schedules
        $loan = EmployeeLoan::create([
            'employee_id' => $this->employee->emp_number,
            'loan_amount' => 5000000,
            'loan_interest' => 1, // 1%
            'loan_months' => 5,
            'loan_date' => '2026-06-22',
            'loan_status' => 1,
        ]);

        // Next unpaid schedule (index 0)
        $loan->schedules()->create([
            'month_number' => 7,
            'year_number' => 2026,
            'amount' => 1000000,
            'loan_interest_schedule' => 0.01,
            'loan_interest_sched_amount' => 10000,
            'loan_total_sched_amount' => 1010000, // Total installment = 1,010,000
            'remaining_amount' => 4000000,
            'paid_amount' => 0,
            'payment_status' => 1, // Unpaid
        ]);

        // Another unpaid schedule (index 1)
        $loan->schedules()->create([
            'month_number' => 8,
            'year_number' => 2026,
            'amount' => 1000000,
            'loan_interest_schedule' => 0.01,
            'loan_interest_sched_amount' => 10000,
            'loan_total_sched_amount' => 1010000,
            'remaining_amount' => 3000000,
            'paid_amount' => 0,
            'payment_status' => 1,
        ]);

        // 2. Fetch the Edit page
        $response = $this->actingAs($this->user)->get(route('salary-settings.edit', $this->employee));
        $response->assertStatus(200);

        // Verify injected component value is the next schedule total (1,010,000)
        $response->assertViewHas('employeeComponents', function ($components) use ($koperasiComponent) {
            $comp = $components->get($koperasiComponent->id_component);
            return $comp && (float)$comp->value_component === 1010000.0;
        });

        // 3. Save salary settings form
        $response = $this->actingAs($this->user)->put(route('salary-settings.update', $this->employee), [
            'components' => [
                $koperasiComponent->id_component => [
                    'value' => '9999999' // Try to send different value
                ]
            ]
        ]);

        $response->assertRedirect(route('salary-settings.index'));

        // Verify stored value in employee_payroll_component is forced to 1010000 (ignoring submitted 9999999)
        $storedSetting = \App\Models\EmployeePayrollComponent::where('emp_number', $this->employee->emp_number)
            ->where('id_component', $koperasiComponent->id_component)
            ->first();
        
        $this->assertNotNull($storedSetting);
        $this->assertEquals(1010000.0, (float)$storedSetting->value_component);
    }
}
