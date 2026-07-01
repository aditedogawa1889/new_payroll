<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOvertime extends Model
{
    use HasFactory;

    protected $table = 'employee_overtime';
    protected $primaryKey = 'id_overtime';

    protected $fillable = [
        'employee_id',
        'period_ot_st',
        'period_ot_en',
        'payroll_ot_date',
        'overtime_hours',
        'overtime_meals',
    ];

    protected $casts = [
        'period_ot_st' => 'date',
        'period_ot_en' => 'date',
        'payroll_ot_date' => 'date',
        'overtime_hours' => 'decimal:2',
        'overtime_meals' => 'decimal:0',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_number');
    }
}
