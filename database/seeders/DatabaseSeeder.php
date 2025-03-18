<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PositionSeeder::class,
            OrderItemStatusSeeder::class,
            OrderStatusSeeder::class,
            RoleSeeder::class, 
            UserSeeder::class,
            TableSeeder::class,
            DishTypeSeeder::class,
            DishSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
