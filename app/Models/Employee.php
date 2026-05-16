<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_number';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'emp_number',
        'employee_id',
        'employee_name',
        'email',
        'join_date',
        'location_current_year',
        'location_future_year',
        'effective_location_year',
        'effective_location_date',
        'job_level',
        'job_title',
        'npwp',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'ktp',
        'gender',
        'bank_account',
        'bank_name',
        'bank_account_name',
        'created_by',
        'updated_by',
    ];
}
