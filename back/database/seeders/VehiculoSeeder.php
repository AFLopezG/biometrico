<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehiculos')->insert([
            [
                'placa' => 'ABC-111',
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'color' => 'Blanco',
                'codcolor' => 'NINGUNO',
                'afiliado_id' => 1,
                'grupo_id' => 1,
            ],
            [
                'placa' => 'ABC-222',
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'color' => 'Blanco',
                'codcolor' => 'NINGUNO',
                'afiliado_id' => 2,
                'grupo_id' => 2,
            ],
        ]);
    }
}
