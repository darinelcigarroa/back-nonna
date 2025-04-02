<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Llamar otros seeders de producción
        $this->call([
            PositionSeeder::class,
            RoleSeeder::class,
            PaymentTypeSeeder::class,
            OrderItemStatusSeeder::class,
            OrderStatusSeeder::class,
            DishTypeSeeder::class,
            TableSeeder::class,
        ]);

        // Buscar el puesto "super admin"
        $position = Position::where('name', 'super admin')->first();
        // Verificar si el usuario ya existe
        $userSuperAdmin = User::where('email', 'admin@admin.com')->first();

        if (!$userSuperAdmin) {
            $employee = Employee::create([
                'name' => 'Darinel',
                'first_surname' => 'Cigarroa',
                'second_surname' => 'de los Santos',
                'position_id' => $position->id,
                'salary' => 0,
            ]);

            $userSuperAdmin = User::create([
                'user_name' => 'admin',
                'email' => 'admin@admin.com',
                'employee_id' => $employee->id,
                'password' => Hash::make('123'),
            ]);
        }

        // Buscar el rol "super-admin" y asignarlo solo si aún no está asignado
        $role = Role::where('name', 'super-admin')->first();

        if ($role && !$userSuperAdmin->hasRole('super-admin')) {
            $userSuperAdmin->assignRole($role);
        }
    }
}
