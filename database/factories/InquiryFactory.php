<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'nombre' => fake()->name(),
            'email' => fake()->email(),
            'telefono' => fake()->phoneNumber(),
            'mensaje' => fake()->paragraph(),
            'read_at' => null,
        ];
    }

    public function leida(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => now(),
        ]);
    }
}
