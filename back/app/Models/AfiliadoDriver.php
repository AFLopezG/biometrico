<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfiliadoDriver extends Model
{
    use HasFactory;
    protected $table = 'afiliado_driver';
    protected $fillable = [
        'afiliado_id',
        'driver_id',
        'fechaingreso',
        'fechabaja',
    ];
    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class);
    }
}
