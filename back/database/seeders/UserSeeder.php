<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [ "name" => "Admin","email" => "admin@test.com","fechaLimite" => "2030-07-29","password" => bcrypt("admin"),],
        ]);
        DB::table('permiso_user')->insert(
            [
                ['user_id'=>1,'permiso_id'=>1],
                ['user_id'=>1,'permiso_id'=>2],
                ['user_id'=>1,'permiso_id'=>3],
                ['user_id'=>1,'permiso_id'=>4],
                ['user_id'=>1,'permiso_id'=>5],
                ['user_id'=>1,'permiso_id'=>6],
                ['user_id'=>1,'permiso_id'=>7],
            ]
        );
    }
}
