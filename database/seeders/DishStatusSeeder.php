<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DishStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dish_statuses')->insert([
            ['id' => 1, 'name' => 'Creado'],
            ['id' => 2, 'name' => 'En cocina'],
            ['id' => 3, 'name' => 'En Preparación'],
            ['id' => 4, 'name' => 'Listo para Servir'],
            ['id' => 5, 'name' => 'Cancelado'],
        ]);
    }
}
