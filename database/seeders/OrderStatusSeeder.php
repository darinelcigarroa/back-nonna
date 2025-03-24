<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'name' => 'pendiente'],
            ['id' => 2, 'name' => 'completado'],
            ['id' => 3, 'name' => 'enviado'],
            ['id' => 4, 'name' => 'pagado'],
            ['id' => 5, 'name' => 'cancelado'],
        ];
        
        foreach ($statuses as $status) {
            OrderStatus::updateOrCreate(
                ['id' => $status['id']], 
                ['name' => $status['name']]
            );
        }
    }
}
