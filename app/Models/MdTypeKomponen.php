<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdTypeKomponen extends Model
{
    use HasFactory;

    protected $table = 'md_type_komponen';
    protected $primaryKey = 'id_type_component';

    protected $fillable = [
        'nama_type_component',
        'is_active',
        'created_by',
        'updated_by'
    ];
}
