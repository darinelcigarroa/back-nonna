<?php

namespace Database\Seeders;

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
        DB::table('order_statuses')->insert([
            ['id' => 1, 'name' => 'Pendiente'],
            ['id' => 2, 'name' => 'Enviado'],
            ['id' => 3, 'name' => 'Pagado'],
            ['id' => 4, 'name' => 'En edición'],
            ['id' => 5, 'name' => 'Cancelado'],
        ]);
    }
}
