<?php

namespace Database\Seeders;

use App\Models\DishType;
use Illuminate\Database\Seeder;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DishType::create(['name' => 'Entrada']);
        DishType::create(['name' => 'Plato Fuerte']);
        DishType::create(['name' => 'Postre']);
        DishType::create(['name' => 'Bebida']);
    }
}
