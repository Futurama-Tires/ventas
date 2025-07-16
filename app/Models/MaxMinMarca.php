<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxMinMarca extends Model
{
    use HasFactory;

    protected $table = 'maxminmarca';

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
