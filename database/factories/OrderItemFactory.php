<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemStatus;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dish = Dish::with('dishType:id,name')->inRandomOrder()->first();

        return [
            'order_id' => Order::factory(),
            'dish_id' => $dish->id,
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $dish->price,
            'dish_name' => $dish->name,
            'dish_type' => $dish->dishType->name,
            'observations' => collect(range(1, 3))->map(fn () => $this->faker->sentence)->toArray(),
            'status_id' => OrderItemStatus::STATUS_IN_KITCHEN
        ];
    }
}
