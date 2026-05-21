<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdComponentPayroll extends Model
{
    use HasFactory;

    protected $table = 'md_component_payroll';
    protected $primaryKey = 'id_component';

    protected $fillable = [
        'nama_component',
        'id_type_component',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function type()
    {
        return $this->belongsTo(MdTypeKomponen::class, 'id_type_component', 'id_type_component');
    }
}
