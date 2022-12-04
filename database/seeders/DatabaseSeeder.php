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

        ]);

        // $this->call([
        //     TipoUsuarioSeeder::class,
        //     //UserSeeder::class,
        // ]);

    }


}
