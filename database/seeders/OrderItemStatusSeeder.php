<?php

namespace Database\Seeders;

use App\Models\OrderItemStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItemStatuses = [
            ['name' => 'Creado'],
            ['name' => 'En cocina'],
            ['name' => 'En preparacion'],
            ['name' => 'Listo para servir'],
            ['name' => 'Cancelado'],
        ];
        
        foreach ($orderItemStatuses as $status) {
            OrderItemStatus::updateOrCreate(
                ['name' => $status['name']], // Condición para verificar si ya existe
                ['name' => $status['name']]  // Los valores a insertar o actualizar
            );
        }        
    }
}
