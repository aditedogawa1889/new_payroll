<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeePayrollComponent;
use App\Services\MathEvaluator;

class PayrollCalculationEngine
{
    protected $mathEvaluator;

    public function __construct(MathEvaluator $mathEvaluator)
    {
        $this->mathEvaluator = $mathEvaluator;
    }

    /**
     * Calculate payroll components and net salary for an employee.
     *
     * @param string $empNumber
     * @return array
     * @throws \InvalidArgumentException
     */
    public function calculate(string $empNumber): array
    {
        $employee = Employee::where('emp_number', $empNumber)->firstOrFail();

        // Get all active payroll settings for the employee along with component definition and type
        $employeeComponents = EmployeePayrollComponent::with(['component.type'])
            ->where('emp_number', $empNumber)
            ->where('is_active', true)
            ->get()
            ->filter(function ($empComp) {
                return $empComp->component && $empComp->component->is_active;
            })
            ->keyBy('id_component');

        if ($employeeComponents->isEmpty()) {
            return [
                'employee' => $employee,
                'components' => [],
                'sorted_order' => [],
                'total_earnings' => 0.0,
                'total_deductions' => 0.0,
                'net_salary' => 0.0,
            ];
        }

        // Build name to component ID map (case-insensitive for variable matching in custom formulas)
        $nameToIdMap = [];
        foreach ($employeeComponents as $empComp) {
            $nameToIdMap[strtolower(trim($empComp->component->nama_component))] = $empComp->id_component;
        }

        // 1. Dependency Graph & In-Degree setup for Topological Sort (Kahn's Algorithm)
        $nodes = [];
        $adj = [];
        $inDegree = [];

        foreach ($employeeComponents as $empComp) {
            $id = $empComp->id_component;
            $nodes[] = $id;
            $adj[$id] = [];
            $inDegree[$id] = 0;
        }

        foreach ($employeeComponents as $empComp) {
            $id = $empComp->id_component;
            $paramType = $empComp->component->component_parameter;
            $deps = [];

            if ($paramType === 'percentage') {
                $deps = $empComp->basis_components ?? [];
            } elseif ($paramType === 'custom') {
                $formula = $empComp->custom_formula ?? '';
                preg_match_all('/\[([^\]]+)\]/', $formula, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $refName) {
                        $refNameClean = strtolower(trim($refName));
                        if (isset($nameToIdMap[$refNameClean])) {
                            $deps[] = $nameToIdMap[$refNameClean];
                        } else {
                            throw new \InvalidArgumentException(
                                "Formula untuk komponen '" . $empComp->component->nama_component . 
                                "' merujuk ke '" . $refName . "' yang tidak aktif atau tidak diatur bagi karyawan ini."
                            );
                        }
                    }
                }
            }

            // Clean & deduplicate dependencies
            $deps = array_unique(array_filter($deps));

            foreach ($deps as $depId) {
                if (in_array($depId, $nodes)) {
                    $adj[$depId][] = $id;
                    $inDegree[$id]++;
                }
            }
        }

        // 2. Perform Topological Sort
        $queue = [];
        foreach ($nodes as $id) {
            if ($inDegree[$id] === 0) {
                $queue[] = $id;
            }
        }

        $sortedOrder = [];
        while (!empty($queue)) {
            $u = array_shift($queue);
            $sortedOrder[] = $u;

            foreach ($adj[$u] as $v) {
                $inDegree[$v]--;
                if ($inDegree[$v] === 0) {
                    $queue[] = $v;
                }
            }
        }

        if (count($sortedOrder) !== count($nodes)) {
            throw new \InvalidArgumentException(
                "Deteksi dependensi melingkar (Circular Dependency) pada pengaturan komponen gaji. Silakan periksa rumus atau basis persentase Anda."
            );
        }

        // 3. Sequentially calculate values in sorted order
        $calculatedValues = [];
        $breakdown = [];

        foreach ($sortedOrder as $id) {
            $empComp = $employeeComponents[$id];
            $component = $empComp->component;
            $paramType = $component->component_parameter;
            $typeName = $component->type->nama_type_component ?? 'Penambahan';

            $val = 0.0;
            $details = [
                'id_component' => $id,
                'nama_component' => $component->nama_component,
                'type' => $typeName,
                'parameter' => $paramType,
                'input_value' => $empComp->value_component,
                'basis_components' => [],
                'custom_formula' => null,
                'expanded_formula' => null,
                'evaluated_value' => 0.0,
            ];

            if ($paramType === 'general') {
                $val = (float) ($empComp->value_component ?? 0.0);
                $details['evaluated_value'] = $val;
            } elseif ($paramType === 'percentage') {
                $percentage = (float) ($empComp->value_component ?? 0.0);
                $basisIds = $empComp->basis_components ?? [];
                $basisSum = 0.0;
                $basisNames = [];

                foreach ($basisIds as $basisId) {
                    if (isset($employeeComponents[$basisId])) {
                        $basisVal = $calculatedValues[$basisId] ?? 0.0;
                        $basisSum += $basisVal;
                        $basisNames[] = $employeeComponents[$basisId]->component->nama_component . ' (' . number_format($basisVal, 2, ',', '.') . ')';
                    }
                }

                $val = $basisSum * ($percentage / 100.0);
                $details['basis_components'] = $basisNames;
                $details['evaluated_value'] = $val;
            } elseif ($paramType === 'custom') {
                $formula = $empComp->custom_formula ?? '';
                $details['custom_formula'] = $formula;

                // Substitute placeholders with calculated values
                preg_match_all('/\[([^\]]+)\]/', $formula, $matches);
                $expandedFormula = $formula;

                if (!empty($matches[1])) {
                    $replacements = [];
                    foreach ($matches[1] as $refName) {
                        $refNameClean = strtolower(trim($refName));
                        $refId = $nameToIdMap[$refNameClean];
                        $refVal = $calculatedValues[$refId] ?? 0.0;
                        $replacements['[' . $refName . ']'] = $refVal;
                    }

                    // Sort replacement keys by length descending to avoid substring collision
                    uksort($replacements, function ($a, $b) {
                        return strlen($b) - strlen($a);
                    });

                    foreach ($replacements as $placeholder => $replVal) {
                        $expandedFormula = str_replace($placeholder, (string)$replVal, $expandedFormula);
                    }
                }

                $details['expanded_formula'] = $expandedFormula;

                try {
                    $val = $this->mathEvaluator->evaluate($expandedFormula);
                } catch (\InvalidArgumentException $e) {
                    throw new \InvalidArgumentException(
                        "Gagal mengevaluasi rumus untuk '" . $component->nama_component . "': " . $e->getMessage()
                    );
                }

                $details['evaluated_value'] = $val;
            }

            $calculatedValues[$id] = $val;
            $breakdown[$id] = $details;
        }

        // 4. Summarize total earnings (Penambahan) vs deductions (Pengurangan)
        $totalEarnings = 0.0;
        $totalDeductions = 0.0;

        foreach ($breakdown as $id => $details) {
            $val = $details['evaluated_value'];
            if ($details['type'] === 'Penambahan') {
                $totalEarnings += $val;
            } elseif ($details['type'] === 'Pengurangan') {
                $totalDeductions += $val;
            }
        }

        $netSalary = $totalEarnings - $totalDeductions;

        // Re-map breakdown to match original sorted order
        $orderedBreakdown = [];
        foreach ($sortedOrder as $id) {
            $orderedBreakdown[] = $breakdown[$id];
        }

        return [
            'employee' => $employee,
            'components' => $orderedBreakdown,
            'sorted_order' => $sortedOrder,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
        ];
    }
}
