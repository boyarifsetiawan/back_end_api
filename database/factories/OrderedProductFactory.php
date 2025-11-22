<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\OrderedProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderedProduct>
 */
class OrderedProductFactory extends Factory
{
    protected $model = OrderedProduct::class;

    public function definition()
    {
        return [
            // 'product_id' => null,
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $this->faker->randomFloat(2, 5, 200),
            'color' => null,
            'size' => null,
        ];
    }
}
