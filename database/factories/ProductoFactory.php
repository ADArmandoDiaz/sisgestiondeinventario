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
            'categoria_id' => \App\Models\Categoria::query()->inRandomOrder()->value('id'),

            // 'sucursal_id' => \App\Models\Sucursal::factory(),
            'codigo' => $this->faker->unique()->numerify('#####'),
            'nombre' => $this->faker->unique()->words(2, true), // <- único


            'description' => $this->faker->sentence(),
            'imagen' => $this->faker->imageUrl(640, 480, 'products'),
            'precio_compra' => $this->faker->randomFloat(2, 10, 100),
            'precio_venta' => $this->faker->randomFloat(2, 15, 200),
            'stock_minimo' => $this->faker->numberBetween(1, 10),
            'stock_maximo' => $this->faker->numberBetween(11, 100),
            'unidad_medida' => $this->faker->randomElement([ 'kg', 'lt', 'm', 'unidad']),
            'estado' => $this->faker->boolean(80), // 80% de probabilidad de ser true
        ];
    }
}
