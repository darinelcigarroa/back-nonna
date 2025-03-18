<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $table = Table::inRandomOrder()->first();
        $user = User::role('waiter')->inRandomOrder()->first();

        return [
            'folio' => $this->faker->unique()->numerify('ORD###'),
            'table_id' => $table->id,
            'num_dinners' => $this->faker->numberBetween(1, 10),
            'user_id' => $user->id,
            'order_status_id' => OrderStatus::PENDING,
            'total_amount' => 0, // Se recalcula después de los order items
        ];
    }
}
