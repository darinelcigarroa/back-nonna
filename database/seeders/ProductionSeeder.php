<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PositionSeeder::class,
            RoleSeeder::class,
            PaymentTypeSeeder::class,
            PositionSeeder::class,
            OrderItemStatusSeeder::class,
            OrderStatusSeeder::class,
            DishTypeSeeder::class,
            TableSeeder::class,
        ]);

        Employee::updateOrCreate([
            'name' => 'Darinel', 
            'first_surname' => 'Cigarroa',
            'second_surname' => 'de los Santos',
            'position_id' => Position::where('name', 'super admin')->first()->id,
            'salary' => 0]
        );
        
        $employee = Employee::whereHas('position', function ($query) {
            $query->where('name', 'super admin');
        })->first();

        $userSuperAdmin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'user_name' => 'admin',
                'email' => 'admin@admin.com',
                'employee_id' => $employee->id,
                'password' => Hash::make('123'),
            ]
        );
     
        $userSuperAdmin->assignRole(Role::where('name', 'super-admin')->first());
    }
}
