<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    protected $fillable = [
        'ci',
        'celular',
        'nombres',
        'foto',
        'grupo',
    ];

    public function afiliados()
    {
        return $this->belongsToMany(Afiliado::class);
    }
    public function afiliadoDriver()
    {
        return $this->hasMany(AfiliadoDriver::class)->with('afiliado');
    }
}
