<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxMinExistencias extends Model
{
    use HasFactory;

    protected $table = 'maxminexistencias';

    protected $fillable = [
        'marca',
        'rin',
        'articulo',
        'descripcion',
        'stock',
    ];
}
