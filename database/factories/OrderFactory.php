<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'shipping_address' => $this->faker->address(),
            'item_count' => 0,
            'total_price' => 0,
            // 'created_date' => now(),
        ];
    }
}
