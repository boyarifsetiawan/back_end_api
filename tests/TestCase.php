<?php

namespace Tests;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * 3. DocBlock untuk Static Analysis (Menghilangkan Garis Merah)
 *
 * Tambahkan properti yang paling sering Anda gunakan dalam pengujian,
 * seperti User, Product, atau endpoint API.
 * @property \App\Models\User $user
 * @property \App\Models\Product $products
 * @property string $api_endpoint
 */
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    protected User $user;
    protected Product $products;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->products = Product::factory()->create();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    protected function productItemStructure(): array
    {
        return [
            'id',
            'title',
            'gender',
            'price',
            'sales_number',
            'discounted_price',
            'created_at',
            'updated_at',
            'category',
            'images',
            'colors',
            'sizes'
        ];
    }
    protected function productListStructure(): array
    {
        return [
            'message',
            'results' => [
                '*' => $this->productItemStructure()
            ],
        ];
    }
}
