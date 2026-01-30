<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        // Instancia manual (esto funciona perfecto)
        $faker = \Faker\Factory::create();

        return [
            'categoria_id' => \App\Models\Categoria::query()->inRandomOrder()->value('id') ?? 1,
            
            'codigo' => $faker->unique()->numerify('#####'),
            
            // SOLUCIÓN AQUÍ: Agregamos 4 números al azar al final del nombre
            // Ejemplo: "Mesa Azul 4829"
            // Esto evita que se repitan los nombres.
            'nombre' => $faker->words(2, true) . ' ' . $faker->numerify('####'), 

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