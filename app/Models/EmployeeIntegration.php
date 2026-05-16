<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeIntegration extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_integration';

    protected $fillable = [
        'id_integ_type',
        'emp_number',
        'employee_id',
        'job_title_id',
        'job_title_future_id',
        'join_date',
        'location_from_id',
        'location_to_id',
        'effective_date',
        'is_processed',
        'created_by',
        'updated_by',
    ];

    public function masterIntegrationType()
    {
        return $this->belongsTo(MasterIntegrationType::class, 'id_integ_type', 'id_integ_type');
    }
}
