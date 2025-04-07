<?php

namespace Database\Seeders;

use App\Models\DishType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dish_types')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Entrada']
        );
        
        DB::table('dish_types')->updateOrInsert(
            ['id' => 2],
            ['name' => 'Plato Fuerte']
        );
        
        DB::table('dish_types')->updateOrInsert(
            ['id' => 3],
            ['name' => 'Postre']
        );
        
        DB::table('dish_types')->updateOrInsert(
            ['id' => 4],
            ['name' => 'Bebida']
        );        
    }
}
