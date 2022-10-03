<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $fillable = [
        'afiliado_id',
        'grupo_id',
        'vehiculo_id',
        'user_id',
        'monto',
        'multa',
        'fecha',
        'hora',
        'impreso',
        'anulado',
    ];
    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
