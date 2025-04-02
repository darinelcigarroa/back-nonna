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
            ['name' => 'super admin', 'visible' => false],
            ['name' => 'chef', 'visible' => true],
            ['name' => 'waiter', 'visible' => true],
        ];
        
        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['name' => $position['name']],
                ['name' => $position['name'], 'visible' => $position['visible']]
             );
        }
    }        
}
