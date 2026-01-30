<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Busca un ID de categoría existente al azar
            'categoria_id' => \App\Models\Categoria::query()->inRandomOrder()->value('id') ?? 1, 

            // CAMBIO CLAVE: Usamos fake() en lugar de $this->faker
            'codigo' => fake()->unique()->numerify('#####'),
            'nombre' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'imagen' => fake()->imageUrl(640, 480, 'products'),
            'precio_compra' => fake()->randomFloat(2, 10, 100),
            'precio_venta' => fake()->randomFloat(2, 15, 200),
            'stock_minimo' => fake()->numberBetween(1, 10),
            'stock_maximo' => fake()->numberBetween(11, 100),
            'unidad_medida' => fake()->randomElement(['kg', 'lt', 'm', 'unidad']),
            'estado' => fake()->boolean(80),
        ];
    }
}