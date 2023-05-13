<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{
    use HasFactory;
    protected $fillable = [
        'ci',
        'codigo',
        'telefono',
        'estado',
        'expedido',
        'nombres',
        'apellidos',
        'dedo1',
        'dedo2',
        'dedo3',
        'fechaing',
    ];

    public function drivers(){
        return $this->belongsToMany(Driver::class);
    }
}
