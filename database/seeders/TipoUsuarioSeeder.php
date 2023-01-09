<?php

namespace Database\Seeders;

use App\Models\Tipo_usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rols = ['superadmin','admin'];

        for($i=0 ; $i<2 ; $i++)
        {
            Tipo_usuario::create([
                'nombre' => $rols[$i],
                'slug' => $rols[$i],
            ]);
        }
    }
}


