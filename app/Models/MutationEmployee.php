<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_number',
        'employee_id',
        'previous_location_name',
        'new_location_name',
        'effective_date',
        'created_by',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_number', 'emp_number');
    }
}
