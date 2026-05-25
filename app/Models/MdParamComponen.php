<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdParamComponen extends Model
{
    use HasFactory;

    protected $table = 'md_param_componen';
    protected $primaryKey = 'id_param_component';

    protected $fillable = [
        'nama_param_component',
    ];

    public function components()
    {
        return $this->hasMany(MdComponentPayroll::class, 'id_param_component', 'id_param_component');
    }
}
