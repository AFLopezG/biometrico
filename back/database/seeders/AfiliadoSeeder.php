<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfiliadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('afiliados')->insert([
            [
                'ci' => '2020',
                'codigo' => 'D1',
                'expedido' => 'LP',
                'nombres' => 'Juan',
                'apellidos' => 'Perez',
                'dedo1' => '123456789',
                'dedo2' => '123456789',
                'dedo3' => '123456789',
                'fechaing' => '2021-10-03',
            ],
            [
                'ci' => '1010',
                'codigo' => 'D2',
                'expedido' => 'LP',
                'nombres' => 'Alejandro ',
                'apellidos' => 'Lopez',
                'dedo1' => '123456789',
                'dedo2' => '123456789',
                'dedo3' => '123456789',
                'fechaing' => '2021-10-03',
            ],
        ]);
    }
}
