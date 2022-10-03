<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grupos')->insert([
            [
                'tipo' => 'GRUPO A Y B',
                'detalle' => 'MINI  Y MICRO',
                'monto' => 100,
                'multa' => 100,
                'rango' => 'SEMANAL',
                'sindical' => 50,
                'seguro' => 10,
                'deportico' => 5,
                'decano' => 3,
            ],
            [
                'tipo' => 'GRUPO C',
                'detalle' => 'FLOTAS',
                'monto' => 200,
                'multa' => 200,
                'rango' => 'MENSUAL',
                'sindical' => 910,
                'seguro' => 50,
                'deportico' => 25,
                'decano' => 15,
            ],
            [
                'tipo' => 'GRUPO D',
                'detalle' => 'SURIBIS',
                'monto' => 200,
                'multa' => 200,
                'rango' => 'SEMANAL',
                'sindical' => 45,
                'seguro' => 10,
                'deportico' => 5,
                'decano' => 3,
            ],
            [
                'tipo' => 'GRUPO E',
                'detalle' => 'TAXIS',
                'monto' => 200,
                'multa' => 200,
                'rango' => 'SEMANAL',
                'sindical' => 20,
                'seguro' => 7,
                'deportico' => 3,
                'decano' => 3,
            ],
        ]);
    }
}
