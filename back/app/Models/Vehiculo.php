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
        'radicatoria',
        'color',
        'codcolor',
        'capacidad',
        'codmovil',
        'afiliado_id',
        'grupo_id',
        'colorRetraso',
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
