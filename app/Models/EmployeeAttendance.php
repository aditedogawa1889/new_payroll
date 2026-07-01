<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendances';
    protected $primaryKey = 'id_attendance';

    protected $fillable = [
        'employee_id',
        'period_st',
        'period_en',
        'payroll_date',
        'attendance_days',
    ];

    protected $casts = [
        'period_st' => 'date',
        'period_en' => 'date',
        'payroll_date' => 'date',
        'attendance_days' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_number');
    }
}
