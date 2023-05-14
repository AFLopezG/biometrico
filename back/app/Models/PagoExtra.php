<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoExtra extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id',
        'fecha',
        'monto',
        'motivo',
    ];
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
