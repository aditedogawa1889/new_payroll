<?php

namespace App\Database;

class CryptableColumnsByTable
{
    public static function decryptableColumnsByTable() {
        return [
            'employee_payroll_component' => [
                'value_component'
            ],
            'employee_loans' => [
                'loan_amount'
            ],
            'employee_loans_schedule' => [
                'amount',
                'loan_interest_sched_amount',
                'loan_total_sched_amount',
                'remaining_amount',
                'paid_amount'
            ]
        ];
    }

    public static function encryptableColumnsByTable() {
        return [
            'employee_payroll_component' => [
                'value_component'
            ],
            'employee_loans' => [
                'loan_amount'
            ],
            'employee_loans_schedule' => [
                'amount',
                'loan_interest_sched_amount',
                'loan_total_sched_amount',
                'remaining_amount',
                'paid_amount'
            ]
        ];
    }
}
