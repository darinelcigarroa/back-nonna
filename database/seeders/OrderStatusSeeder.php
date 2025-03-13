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
            ['id' => 1, 'name' => 'Pendiente'],
            ['id' => 2, 'name' => 'Completado'],
            ['id' => 3, 'name' => 'Enviado'],
            ['id' => 4, 'name' => 'Pagado'],
            ['id' => 5, 'name' => 'En edición'],
            ['id' => 6, 'name' => 'Cancelado'],
        ];
        
        foreach ($statuses as $status) {
            OrderStatus::updateOrCreate(
                ['id' => $status['id']], 
                ['name' => $status['name']]
            );
        }
    }
}
