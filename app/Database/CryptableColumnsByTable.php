<?php

namespace App\Database;

class CryptableColumnsByTable
{
    public static function decryptableColumnsByTable() {
        return [
            'employee_payroll_component' => [
                'value_component'
            ]
        ];
    }

    public static function encryptableColumnsByTable() {
        return [
            'employee_payroll_component' => [
                'value_component'
            ]
        ];
    }
}
