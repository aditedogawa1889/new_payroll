<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdComponentPayroll extends Model
{
    use HasFactory;

    protected $table = 'md_component_payroll';
    protected $primaryKey = 'id_component';

    protected $with = ['parameter'];

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('is_delete', 0);
        });
    }

    protected $fillable = [
        'nama_component',
        'id_type_component',
        'id_param_component',
        'component_parameter',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by'
    ];

    public function type()
    {
        return $this->belongsTo(MdTypeKomponen::class, 'id_type_component', 'id_type_component');
    }

    public function parameter()
    {
        return $this->belongsTo(MdParamComponen::class, 'id_param_component', 'id_param_component');
    }

    public function getComponentParameterAttribute()
    {
        return $this->parameter?->nama_param_component;
    }

    public function setComponentParameterAttribute($value)
    {
        $map = [
            'general' => 1,
            'percentage' => 2,
            'custom' => 3
        ];
        if (isset($map[$value])) {
            $this->attributes['id_param_component'] = $map[$value];
        }
    }
}
