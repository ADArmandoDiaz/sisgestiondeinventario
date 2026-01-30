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
        // SOLUCIÓN DEFINITIVA: Instanciamos Faker manualmente
        // Esto evita el error de "null" y el error de "undefined function"
        $faker = \Faker\Factory::create();

        return [
            'categoria_id' => \App\Models\Categoria::query()->inRandomOrder()->value('id') ?? 1,
            
            // Usamos la variable $faker que acabamos de crear arriba
            'codigo' => $faker->unique()->numerify('#####'),
            'nombre' => $faker->unique()->words(2, true),
            'description' => $faker->sentence(),
            'imagen' => $faker->imageUrl(640, 480, 'products'),
            'precio_compra' => $faker->randomFloat(2, 10, 100),
            'precio_venta' => $faker->randomFloat(2, 15, 200),
            'stock_minimo' => $faker->numberBetween(1, 10),
            'stock_maximo' => $faker->numberBetween(11, 100),
            'unidad_medida' => $faker->randomElement(['kg', 'lt', 'm', 'unidad']),
            'estado' => $faker->boolean(80),
        ];
    }
}