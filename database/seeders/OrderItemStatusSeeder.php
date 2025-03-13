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
        OrderItemStatus::create(['name' => 'Entrada']);
        OrderItemStatus::create(['name' => 'Plato Fuerte']);
        OrderItemStatus::create(['name' => 'Postre']);
        OrderItemStatus::create(['name' => 'Bebida']);
    }
}
