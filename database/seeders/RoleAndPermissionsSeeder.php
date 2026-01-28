<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'encargado']);
        Role::firstOrCreate(['name' => 'auditor']);

        // Permisos de ejemplo (puedes agregar más)
        Permission::firstOrCreate(['name' => 'crear']);
        Permission::firstOrCreate(['name' => 'ver']);
        Permission::firstOrCreate(['name' => 'editar']);
        Permission::firstOrCreate(['name' => 'eliminar']);
        
        // Permission::firstOrCreate(['name' => 'gestion usuarios']);
        Permission::firstOrCreate(['name' => 'permisos usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios']);
        Permission::firstOrCreate(['name' => 'respaldar datos']);

        // Asignar permisos a roles si quieres
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

        $encargado = Role::findByName('encargado');
        $encargado->givePermissionTo(['crear', 'editar','ver']);

        $auditor = Role::findByName('auditor');
        $auditor->givePermissionTo('ver');
        // Auditor solo visualiza, sin permisos de edición
    }
}
