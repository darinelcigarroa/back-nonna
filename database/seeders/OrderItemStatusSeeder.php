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
        OrderItemStatus::create(['name' => 'Creado']);
        OrderItemStatus::create(['name' => 'En cocina']);
        OrderItemStatus::create(['name' => 'En preparacion']);
        OrderItemStatus::create(['name' => 'Listo para servir']);
        OrderItemStatus::create(['name' => 'Cancelado']);
    }
}
