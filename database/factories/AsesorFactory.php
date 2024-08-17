<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesor>
 */
class AsesorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtén un usuario existente o crea uno nuevo
        $user = User::where('rol', 2)->inRandomOrder()->first() ?? User::factory()->withPersonalTeam();

        return [
            //'usuario' => fake()->name(),
            //'usuario' => $this->faker->userName,
            'nombre' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'correo' => fake()->email(),
            //'user_id' => User::factory()->withPersonalTeam(), // Para cada asesor crearme un usuario y asegnar el id de usuario a este asesor
            'user_id' => $user,
            'telefono' => $this->faker->numerify('33########'),

            //'escuela' => fake()-> sentence(),
        ];
    }
}
