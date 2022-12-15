<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('permisos')->insert([
            ['nombre' => 'Usuarios'],
            ['nombre' => 'Afiliados'],
            ['nombre' => 'Grupos'],
            ['nombre' => 'Vehiculos'],
            ['nombre' => 'Pagos'],
            ['nombre' => 'Impresion'],
        ]);
    }
}
