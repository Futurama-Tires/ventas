<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxMinRin extends Model
{
    use HasFactory;

    protected $table = 'maxminrin';

    protected $fillable = [
        'marca',
        'rin',
        'articulo',
        'descripcion',
        'stock',
        'm1',
        'm2',
        'm3',
        'm4',
        'total',
    ];
}
