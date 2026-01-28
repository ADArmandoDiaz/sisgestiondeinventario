<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_empresa' => $this->faker->company(),
            'contacto_nombre' => $this->faker->name(),
            'contacto_telefono' => $this->faker->phoneNumber(),
            'contacto_email' => $this->faker->unique()->safeEmail(),
            'direccion' => $this->faker->address(),
        ];
    }
}
