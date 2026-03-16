<?php

namespace Database\Factories;

use App\Models\Inmobiliaria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inmobiliaria>
 */
class InmobiliariaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'inmobiliaria']),
            'nombre' => fake()->company() . ' Propiedades',
            'descripcion' => fake()->paragraph(),
            'telefono' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'logo' => null,
            'direccion' => fake()->streetAddress(),
            'ciudad' => fake()->randomElement(['Montevideo', 'Punta del Este', 'Colonia', 'Maldonado']),
            'sitio_web' => fake()->url(),
            'is_approved' => false,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }
}
