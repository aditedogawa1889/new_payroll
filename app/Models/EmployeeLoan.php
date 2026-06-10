<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLoan extends Model
{
    use HasFactory;

    protected $table = 'employee_loans';
    protected $primaryKey = 'loan_id';
    
    // Disable standard timestamps as we use custom columns
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'loan_amount',
        'loan_date',
        'loan_description',
        'loan_status',
        'loan_created_at',
        'loan_created_by',
        'loan_updated_at',
        'loan_updated_by',
    ];

    protected $casts = [
        'loan_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_number');
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeLoanSchedule::class, 'loan_id', 'loan_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->loan_created_at = now();
            $model->loan_created_by = auth()->user()->name ?? 'system';
            $model->loan_updated_at = now();
            $model->loan_updated_by = auth()->user()->name ?? 'system';
        });

        static::updating(function ($model) {
            $model->loan_updated_at = now();
            $model->loan_updated_by = auth()->user()->name ?? 'system';
        });
    }
}
