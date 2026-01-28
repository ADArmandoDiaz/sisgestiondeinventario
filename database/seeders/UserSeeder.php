<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    public function run()
    {
       $admin = User::firstOrCreate(
    ['email' => 'armandojesusdiazpizarro@gmail.com'],
    [
        'name' => 'Armando',
        'password' => bcrypt('Arma2006.')
    ]
);
$admin->assignRole('admin');

$encargado = User::firstOrCreate(
    ['email' => 'encargado@gmail.com'],
    [
        'name' => 'Encargado',
        'password' => bcrypt('Encargado123')
    ]
);
$encargado->assignRole('encargado');

$auditor = User::firstOrCreate(
    ['email' => 'auditor@gmail.com'],
    [
        'name' => 'Auditor',
        'password' => bcrypt('Auditor123')
    ]
);
$auditor->assignRole('auditor');
    }
}