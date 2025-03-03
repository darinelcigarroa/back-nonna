<?php

namespace Database\Seeders;

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
        $userSuperAdmin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Mario',
                'first_surname' => 'Cantú',
                'second_surname' => 'González',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123'),
            ]
        );
        $userWaiter = User::updateOrCreate(
            ['email' => 'mesero@mesero.com'],
            [
                'name' => 'Mesero 1',
                'first_surname' => 'Cigarroa',
                'second_surname' => 'de los Santos',
                'email' => 'mesero@mesero.com',
                'password' => Hash::make('123'),
            ]
        );
        $userChef = User::updateOrCreate(
            ['email' => 'chef@chef.com'],
            [
                'name' => 'Chef 1',
                'first_surname' => 'Wilner',
                'second_surname' => 'Sanches',
                'email' => 'chef@chef.com',
                'password' => Hash::make('123'),
            ]
        );
   
        $userSuperAdmin->assignRole(Role::where('name', 'super-admin')->first());
        $userWaiter->assignRole(Role::where('name', 'waiter')->first());
        $userChef->assignRole(Role::where('name', 'chef')->first());
    }
}
