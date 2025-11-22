<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        // $keywords = [
        //     'hoodie',
        //     'bag',
        //     'shoes',
        //     'jacket',
        //     'accessories',
        //     'shorts'
        // ];

        // $keyword = $this->faker->randomNumber(1);

        // ✅ Menggunakan Unsplash Source
        // $imageUrl = "https://source.unsplash.com/640x480";


        return [
            // 'product_id' => null,
            'url' => $this->faker->imageUrl()
        ];
    }
}
