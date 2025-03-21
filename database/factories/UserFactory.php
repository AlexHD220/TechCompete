<?php

namespace Database\Factories;

use App\Models\Administrador;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    // Modelo utilizado para crear factorys
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //Administrador::factory()->count(3)->create(); //--> generar informacion falsa

        // Crear un Administrador 
        //$administrador = Administrador::factory()->create();

        return [
            //'name' => $this->faker->name(),
            'name' => $this->faker->company(),
            //'email' => $this->faker->unique()->safeEmail(),
            'email' => fake()->unique()->email(),
            'email_verified_at' => now(),
            'password' =>Hash::make('Pruebas.tc23'),
            'rol' => 1,
            //'roleable_id' => $administrador->id,
            //'roleable_type' => get_class($administrador),
            //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            //'remember_token' => Str::random(10),
            'remember_token' => null,
            'profile_photo_path' => null,
            //'current_team_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
