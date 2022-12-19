<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TipoUsuarioSeeder::class,
            UserSeeder::class,
        ]);

        DB::table('users')->insert([
            'first_name' => 'Majo',
            'last_name' => 'Chalá',
            'email' => 'majo@gmail.com',
            'password' => bcrypt('secret'),
            'tipo_usuario_id' => '1',
            'linkedin' => 'https://linkedin.majo.com',
            'personal_phone' => '0999999999',

        ]);

        DB::table('users')->insert([
            'first_name' => 'Eduardo',
            'last_name' => 'Farinango',
            'email' => 'eduardo@gmail.com',
            'password' => bcrypt('secret'),
            'tipo_usuario_id' => '1',
            'linkedin' => 'https://linkedin.eduardo.com',
            'personal_phone' => '0999999998',

        ]);

        // $this->call([
        //     TipoUsuarioSeeder::class,
        //     //UserSeeder::class,
        // ]);

    }


}
