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
            'nombre' => $this->faker->title,
            'descripcion'  => $this->faker->title,
            'categoria'  => $this->faker->title,
            'direccion' => $this->faker->streetAddress,
            'cobertura'  => $this->faker->text(100),
            'pagina_web' => $this->faker->text(100),
            'telefono' => '09' . $this->faker->randomNumber(8),
            'whatsapp' => '09' . $this->faker->randomNumber(8),
            'facebook' => $this->faker->text(50),
            'instagram' => $this->faker->text(50),
            'porcentaje' => $this->faker->randomFloat(0, 50) . '%',
        ];
    }
}
