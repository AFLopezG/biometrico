<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora',
        'afiliado_id',
    ];
    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class);
    }
}
