<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\EncryptedRouteKey;

class Employee extends Model
{
    use HasFactory, EncryptedRouteKey;

    protected $primaryKey = 'emp_number';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('is_delete', 0);
        });
    }

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
        'termination_date',
        'last_payroll_date',
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
        'is_delete',
        'is_set_salary',
        'created_by',
        'updated_by',
    ];

    public function terminations()
    {
        return $this->hasMany(TerminationEmployee::class, 'emp_number', 'emp_number');
    }

    public function promotions()
    {
        return $this->hasMany(PromotionEmployee::class, 'emp_number', 'emp_number');
    }

    public function demotions()
    {
        return $this->hasMany(DemotionEmployee::class, 'emp_number', 'emp_number');
    }

    public function mutations()
    {
        return $this->hasMany(MutationEmployee::class, 'emp_number', 'emp_number');
    }

    public function loans()
    {
        return $this->hasMany(EmployeeLoan::class, 'employee_id', 'emp_number');
    }
}
