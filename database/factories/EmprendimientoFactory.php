<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Emprendimiento>
 */
class EmprendimientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'rol_esfot' => $this->faker->randomElement(['Estudiante', 'Docente', 'Administrativo', 'Trabajador']),
            'nombre' => $this->faker->randomElement(['Estudiante', 'Docente', 'Administrativo', 'Trabajador', '']),
            'descripcion'  => $this->faker->title,
            'categoria'  => $this->faker->randomElement(['Alimentación', 'Hogar y Limpieza', 'Salud', 'Tecnología', 'Otros']),
            'direccion' => $this->faker->randomElement(['Quito centro', 'Quito norte', 'Quito sur']),
            'cobertura'  => $this->faker->randomElement(['Quito centro', 'Quito norte', 'Quito sur', 'Cumbaya - Tumbaco', 'Valle de los chillos']),
            'pagina_web' => 'https://www.' . $this->faker->firstName() . '.com',
            'telefono' => '09' . $this->faker->randomNumber(8),
            'whatsapp' => '09' . $this->faker->randomNumber(8),
            'facebook' => 'https://facebook.' . $this->faker->firstName() . '.com',
            'instagram' =>'https://instagram.' . $this->faker->firstName() . '.com',
            'descuento' => $this->faker->randomFloat(0, 2) . '%',
        ];
    }
}
