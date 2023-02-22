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
            //UserSeeder::class,
            //EmprendimientoSeeder::class,
        ]);

        // DB::table('users')->insert([
        //     'first_name' => 'Majo',
        //     'last_name' => 'Chalá',
        //     'email' => 'majo@gmail.com',
        //     'password' => bcrypt('secret'),
        //     'tipo_usuario_id' => '1',
        //     'linkedin' => 'https://linkedin.majo.com',
        //     'personal_phone' => '0999999999',

        // ]);

        DB::table('users')->insert([
            'first_name' => 'superadmin',
            'last_name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('secret'),
            'tipo_usuario_id' => '1',
            'linkedin' => 'https://linkedin.superadmin.com',
            'personal_phone' => '0999999998',

        ]);

        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
            'tipo_usuario_id' => '2',
            'linkedin' => 'https://linkedin.admin.com',
            'personal_phone' => '0999995555',

        ]);

        // DB::table('emprendimientos')->insert([
        //     'rol_esfot' => 'Estudiante',
        //     'nombre' => "Fausto's coffee",
        //     'descripcion'  => 'Emprendimiento de cafe.',
        //     'categoria'  => 'Alimentación',
        //     'direccion' => 'Eugenio espejo MZ:10 y Carlos
        //     Montúfar(norte de Quito)',
        //     'cobertura'  => 'Todo el país',
        //     'telefono' => '023447135',
        //     'whatsapp' => '0983873954',
        //     'facebook' => 'https://www.facebook.com/Caf%C3%A9-Lojano-de-Altura-105393597659873/',

        // ]);

        // DB::table('emprendimientos')->insert([
        //     'rol_esfot' => 'Estudiante',
        //     'nombre' => 'Le Cosine Gourmet',
        //     'descripcion'  => 'Comidas gourmet',
        //     'categoria'  => 'Alimentación',
        //     'direccion' => 'Turubamba bajo calle borbón y OE2D. Sector Quicentro Sur',
        //     'cobertura'  => 'Quito Sur',
        //     'telefono' => '0997544961',
        //     'whatsapp' => '0984199337',
        //     'facebook' => 'https://www.facebook.com/foodandjuicelovers/',
        //     'instagram' =>'https://www.instagram.com/foodandjuicelovers/',

        // ]);

        // $this->call([
        //     TipoUsuarioSeeder::class,
        //     //UserSeeder::class,
        // ]);

    }


}
