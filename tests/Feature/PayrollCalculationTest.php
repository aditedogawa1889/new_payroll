<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeePayrollComponent;
use App\Models\MdComponentPayroll;
use App\Models\MdTypeKomponen;
use App\Services\MathEvaluator;
use App\Services\PayrollCalculationEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayrollCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected $mathEvaluator;
    protected $calculationEngine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mathEvaluator = new MathEvaluator();
        $this->calculationEngine = new PayrollCalculationEngine($this->mathEvaluator);
    }

    /**
     * Test MathEvaluator basic arithmetic parsing.
     */
    public function test_math_evaluator_evaluates_basic_arithmetic(): void
    {
        $this->assertEquals(14.0, $this->mathEvaluator->evaluate('2 + 3 * 4'));
        $this->assertEquals(20.0, $this->mathEvaluator->evaluate('(2 + 3) * 4'));
        $this->assertEquals(5.0, $this->mathEvaluator->evaluate('10 / 2'));
        $this->assertEquals(4.0, $this->mathEvaluator->evaluate('10 - 2 * 3'));
        $this->assertEquals(1.0, $this->mathEvaluator->evaluate('5 % 2'));
    }

    /**
     * Test MathEvaluator float and decimal parsing.
     */
    public function test_math_evaluator_evaluates_floats(): void
    {
        $this->assertEquals(21.0, $this->mathEvaluator->evaluate('10.5 * 2'));
        $this->assertEquals(5.0, $this->mathEvaluator->evaluate('10.5 / 2.1'));
        $this->assertEquals(0.05, $this->mathEvaluator->evaluate('5 / 100'));
    }

    /**
     * Test MathEvaluator unary operators (+ and -).
     */
    public function test_math_evaluator_handles_unary_operators(): void
    {
        $this->assertEquals(5.0, $this->mathEvaluator->evaluate('-5 + 10'));
        $this->assertEquals(-50.0, $this->mathEvaluator->evaluate('10 * -5'));
        $this->assertEquals(5.0, $this->mathEvaluator->evaluate('+5'));
        $this->assertEquals(-5.0, $this->mathEvaluator->evaluate('-(2 + 3)'));
    }

    /**
     * Test MathEvaluator error states.
     */
    public function test_math_evaluator_throws_on_errors(): void
    {
        // Division by zero
        $this->expectException(\InvalidArgumentException::class);
        $this->mathEvaluator->evaluate('10 / 0');
    }

    public function test_math_evaluator_throws_on_modulo_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->mathEvaluator->evaluate('10 % 0');
    }

    public function test_math_evaluator_throws_on_invalid_characters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->mathEvaluator->evaluate('2 + 3 * a');
    }

    public function test_math_evaluator_throws_on_mismatched_parentheses(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->mathEvaluator->evaluate('(2 + 3 * 4');
    }

    /**
     * Test PayrollCalculationEngine sorting and evaluating correctness.
     */
    public function test_payroll_engine_calculates_correctly_with_topological_sort(): void
    {
        // 1. Create component types
        $additionType = MdTypeKomponen::create([
            'nama_type_component' => 'Penambahan',
            'is_active' => true,
        ]);
        $deductionType = MdTypeKomponen::create([
            'nama_type_component' => 'Pengurangan',
            'is_active' => true,
        ]);

        // 2. Create salary component master data
        $gajiPokok = MdComponentPayroll::create([
            'nama_component' => 'Gaji Pokok',
            'id_type_component' => $additionType->id_type_component,
            'component_parameter' => 'general',
            'is_active' => true,
        ]);
        $tunjanganMakan = MdComponentPayroll::create([
            'nama_component' => 'Tunjangan Makan',
            'id_type_component' => $additionType->id_type_component,
            'component_parameter' => 'general',
            'is_active' => true,
        ]);
        $tunjanganTransport = MdComponentPayroll::create([
            'nama_component' => 'Tunjangan Transport',
            'id_type_component' => $additionType->id_type_component,
            'component_parameter' => 'custom',
            'is_active' => true,
        ]);
        $bonus = MdComponentPayroll::create([
            'nama_component' => 'Bonus',
            'id_type_component' => $additionType->id_type_component,
            'component_parameter' => 'custom',
            'is_active' => true,
        ]);
        $pph21 = MdComponentPayroll::create([
            'nama_component' => 'PPh21',
            'id_type_component' => $deductionType->id_type_component,
            'component_parameter' => 'percentage',
            'is_active' => true,
        ]);

        // 3. Create employee
        $employee = Employee::create([
            'emp_number' => 8888,
            'employee_id' => 'EMP-8888',
            'employee_name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
        ]);

        // 4. Assign payroll components to employee with specific values, bases, and formulas
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $gajiPokok->id_component,
            'value_component' => 10000000.0,
            'is_active' => true,
        ]);
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $tunjanganMakan->id_component,
            'value_component' => 500000.0,
            'is_active' => true,
        ]);
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $tunjanganTransport->id_component,
            'custom_formula' => '[Tunjangan Makan] * 1.5',
            'is_active' => true,
        ]);
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $bonus->id_component,
            'custom_formula' => '([Gaji Pokok] + [Tunjangan Makan]) * 0.1',
            'is_active' => true,
        ]);
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $pph21->id_component,
            'value_component' => 5.0, // 5%
            'basis_components' => [$gajiPokok->id_component, $bonus->id_component],
            'is_active' => true,
        ]);

        // 5. Run calculation
        $result = $this->calculationEngine->calculate($employee->emp_number);

        // Verification:
        // Gaji Pokok = 10,000,000.0
        // Tunjangan Makan = 500,000.0
        // Tunjangan Transport = 500,000.0 * 1.5 = 750,000.0
        // Bonus = (10,000,000.0 + 500,000.0) * 0.1 = 1,050,000.0
        // Total Earnings = 10,000,000 + 500,000 + 750,000 + 1,050,000 = 12,300,000.0
        // PPh21 = (Gaji Pokok + Bonus) * 5% = (10,000,000.0 + 1,050,000.0) * 0.05 = 552,500.0
        // Total Deductions = 552,500.0
        // Net Salary = 12,300,000.0 - 552,500.0 = 11,747,500.0

        $this->assertEquals(12300000.0, $result['total_earnings']);
        $this->assertEquals(552500.0, $result['total_deductions']);
        $this->assertEquals(11747500.0, $result['net_salary']);

        // Check topological order by index
        $sortedComps = $result['components'];
        $orderOfNames = array_map(fn($c) => $c['nama_component'], $sortedComps);

        // Gaji Pokok & Tunjangan Makan must appear before Tunjangan Transport, Bonus, and PPh21
        $idxGaji = array_search('Gaji Pokok', $orderOfNames);
        $idxMakan = array_search('Tunjangan Makan', $orderOfNames);
        $idxTransport = array_search('Tunjangan Transport', $orderOfNames);
        $idxBonus = array_search('Bonus', $orderOfNames);
        $idxPph = array_search('PPh21', $orderOfNames);

        $this->assertLessThan($idxTransport, $idxMakan);
        $this->assertLessThan($idxBonus, $idxGaji);
        $this->assertLessThan($idxBonus, $idxMakan);
        $this->assertLessThan($idxPph, $idxGaji);
        $this->assertLessThan($idxPph, $idxBonus);
    }

    /**
     * Test PayrollCalculationEngine detects circular dependencies.
     */
    public function test_payroll_engine_throws_exception_on_circular_dependency(): void
    {
        // 1. Create component type
        $additionType = MdTypeKomponen::create([
            'nama_type_component' => 'Penambahan',
            'is_active' => true,
        ]);

        // 2. Create salary components that refer to each other
        $compA = MdComponentPayroll::create([
            'nama_component' => 'Komponen A',
            'id_type_component' => $additionType->id_type_component,
            'component_parameter' => 'custom',
            'is_active' => true,
        ]);
        $compB = MdComponentPayroll::create([
            'nama_component' => 'Komponen B',
            'id_type_component' => $additionType->id_type_component,
            'component_parameter' => 'custom',
            'is_active' => true,
        ]);

        // 3. Create employee
        $employee = Employee::create([
            'emp_number' => 9999,
            'employee_id' => 'EMP-9999',
            'employee_name' => 'Circular Joe',
            'email' => 'circular@example.com',
        ]);

        // 4. Set formulas to depend on each other: A depends on B, B depends on A
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $compA->id_component,
            'custom_formula' => '[Komponen B] * 2',
            'is_active' => true,
        ]);
        EmployeePayrollComponent::create([
            'emp_number' => $employee->emp_number,
            'id_component' => $compB->id_component,
            'custom_formula' => '[Komponen A] * 0.5',
            'is_active' => true,
        ]);

        // 5. Calculate and assert circular dependency exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('dependensi melingkar');

        $this->calculationEngine->calculate($employee->emp_number);
    }
}
