<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::firstOrCreate(['nombre' => 'Productos terminados']);
        Categoria::firstOrCreate(['nombre' => 'Materias primas']);
    }
}
