<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(['super-admin', 'chef', 'waiter'])
        ->each(fn($role) => Role::updateOrCreate(
            ['name' => $role], // Condición para verificar si ya existe
            ['name' => $role]  // Los valores a insertar o actualizar
        ));    
    }
}
