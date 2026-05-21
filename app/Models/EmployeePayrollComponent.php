<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayrollComponent extends Model
{
    use HasFactory;

    protected $table = 'employee_payroll_component';
    protected $primaryKey = 'id_emp_payroll_comp';

    protected $fillable = [
        'emp_number',
        'id_component',
        'value_component',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_number', 'emp_number');
    }

    public function component()
    {
        return $this->belongsTo(MdComponentPayroll::class, 'id_component', 'id_component');
    }
}
