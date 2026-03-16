<?php

namespace Database\Factories;

use App\Models\Inmobiliaria;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $tipo = fake()->randomElement(['casa', 'apartamento', 'local', 'terreno', 'oficina']);
        $operacion = fake()->randomElement(['venta', 'alquiler', 'ambas']);

        return [
            'inmobiliaria_id' => Inmobiliaria::factory()->approved(),
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->paragraph(),
            'tipo' => $tipo,
            'operacion' => $operacion,
            'precio_venta' => in_array($operacion, ['venta', 'ambas']) ? fake()->numberBetween(50000, 500000) : null,
            'precio_alquiler' => in_array($operacion, ['alquiler', 'ambas']) ? fake()->numberBetween(500, 3000) : null,
            'moneda' => 'USD',
            'estado' => 'disponible',
            'dormitorios' => in_array($tipo, ['terreno']) ? 0 : fake()->numberBetween(1, 4),
            'banos' => in_array($tipo, ['terreno']) ? 0 : fake()->numberBetween(1, 3),
            'superficie_total' => fake()->numberBetween(50, 500),
            'superficie_construida' => fake()->numberBetween(40, 300),
            'garage' => fake()->boolean(),
            'piscina' => fake()->boolean(20),
            'direccion' => fake()->streetAddress(),
            'barrio' => fake()->randomElement(['Pocitos', 'Punta Carretas', 'Carrasco', 'Cordón']),
            'ciudad' => 'Montevideo',
            'latitud' => fake()->latitude(-35.0, -34.8),
            'longitud' => fake()->longitude(-56.3, -56.0),
            'destacado' => false,
            'views_count' => 0,
        ];
    }

    public function disponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'disponible',
        ]);
    }

    public function destacado(): static
    {
        return $this->state(fn (array $attributes) => [
            'destacado' => true,
        ]);
    }
}
