<?php

namespace Database\Factories;

use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductColor>
 */
class ProductColorFactory extends Factory
{
    protected $model = ProductColor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->colorName(),
            'rgb' => [$this->faker->numberBetween(0, 255), $this->faker->numberBetween(0, 255), $this->faker->numberBetween(0, 255)],
        ];
    }
}
