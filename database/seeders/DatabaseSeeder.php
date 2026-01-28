<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Sucursal;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed de sucursales, categorías, productos y proveedores
        // Sucursal::factory(10)->create();
        $this->call(CategoriaSeeder::class);
        Producto::factory(200)->create();
        Proveedor::factory(20)->create();

        // Sembrar roles y permisos primero
        $this->call(RoleAndPermissionsSeeder::class);

        // Sembrar usuarios con roles
        $this->call(UserSeeder::class);
    }
}