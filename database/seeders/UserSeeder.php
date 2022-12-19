<?php

namespace Database\Seeders;

use App\Models\Tipo_usuario;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //administrador
        $superadmin = Tipo_usuario::where('nombre', 'superadmin')->first();
        User::factory()->for($superadmin)->count(5)->create();

        //miembro de la comisión 
        $admin = Tipo_usuario::where('nombre', 'admin')->first();
        User::factory()->for($admin)->count(5)->create();

        //PENDIENTE
        // $otro = Tipo_usuario::where('name', 'otro')->first();
        // User::factory()->for($otro)->count(5)->create();
        



    }
}
