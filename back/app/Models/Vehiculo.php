<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    protected $fillable = [
        'placa',
        'tipo',
        'modelo',
        'marca',
        'color',
        'codcolor',
        'capacidad',
        'afiliado_id',
        'grupo_id'
    ];

    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
