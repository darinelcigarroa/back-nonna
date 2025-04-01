<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'chef'],
            ['name' => 'waiter'],
        ];
        
        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['name' => $position['name']], // Condición para verificar si ya existe
                ['name' => $position['name']]  // Los valores a insertar o actualizar
            );
        }
    }        
}
