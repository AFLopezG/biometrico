<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $fillable=[
        'tipo',
        'detalle',
        'monto',
        'multa',
        'rango',
        'sindical',
        'seguro',
        'deportico',
        'decano',
    ];
}
