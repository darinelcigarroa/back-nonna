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
            // Catalogs
            PaymentTypeSeeder::class,
            PositionSeeder::class,
            OrderItemStatusSeeder::class,
            OrderStatusSeeder::class,
            DishTypeSeeder::class,
            // App
            RoleSeeder::class, 
            UserSeeder::class,
            TableSeeder::class,
            DishSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
