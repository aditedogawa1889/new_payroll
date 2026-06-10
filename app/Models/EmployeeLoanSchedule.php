<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLoanSchedule extends Model
{
    use HasFactory;

    protected $table = 'employee_loans_schedule';
    protected $primaryKey = 'loan_schedule_id';
    
    // Disable standard timestamps as we use custom columns
    public $timestamps = false;

    protected $fillable = [
        'loan_id',
        'month_number',
        'year_number',
        'amount',
        'remaining_amount',
        'paid_amount',
        'payment_date',
        'payment_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function loan()
    {
        return $this->belongsTo(EmployeeLoan::class, 'loan_id', 'loan_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = now();
            $model->created_by = auth()->user()->name ?? 'system';
            $model->updated_at = now();
            $model->updated_by = auth()->user()->name ?? 'system';
        });

        static::updating(function ($model) {
            $model->updated_at = now();
            $model->updated_by = auth()->user()->name ?? 'system';
        });
    }
}
