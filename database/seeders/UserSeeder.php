<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->count(5)->create();

        $userSuperAdmin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'user_name' => 'admin',
                'email' => 'admin@admin.com',
                'employee_id' => Employee::factory()->create()->id,
                'password' => Hash::make('123'),
            ]
        );
        $userWaiter = User::updateOrCreate(
            ['email' => 'mesero@mesero.com'],
            [
                'user_name' => 'Mesero 1',
                'email' => 'mesero@mesero.com',
                'employee_id' => Employee::factory()->create()->id,
                'password' => Hash::make('123'),
            ]
        );
        $userChef = User::updateOrCreate(
            ['email' => 'chef@chef.com'],
            [
                'user_name' => 'Chef 1',
                'email' => 'chef@chef.com',
                'employee_id' => Employee::factory()->create()->id,
                'password' => Hash::make('123'),
            ]
        );
   
        $userSuperAdmin->assignRole(Role::where('name', 'super-admin')->first());
        $userWaiter->assignRole(Role::where('name', 'waiter')->first());
        $userChef->assignRole(Role::where('name', 'chef')->first());

    }
}
