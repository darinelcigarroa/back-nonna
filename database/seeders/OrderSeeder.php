<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 10 órdenes con 3-5 order items cada una
        Order::factory()
            ->count(30)
            ->has(OrderItem::factory()->count(rand(5, 10))) // Genera de 3 a 5 items por orden
            ->create()
            ->each(function ($order) {
                // Calcular el total basado en los order items
                $total = $order->orderItems->sum(fn ($item) => $item->price * $item->quantity);
                $order->update(['total_amount' => $total]);
            });
    }
}
