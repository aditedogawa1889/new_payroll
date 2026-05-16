<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterIntegrationType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_integ_type';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'is_active',
    ];

    public function integrations()
    {
        return $this->hasMany(EmployeeIntegration::class, 'id_integ_type', 'id_integ_type');
    }
}
