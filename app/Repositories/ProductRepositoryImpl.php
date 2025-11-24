<?php


namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use App\Interface\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class ProductRepositoryImpl implements ProductRepositoryInterface
{

    public function getAllProducts(): Collection
    {
        $products = Product::latest()->get();
        $products->load(['category', 'images', 'colors', 'sizes']);
        return $products;
    }

    public function getProductById(int $productId): ?Product
    {
        $product = Product::find($productId);
        if ($product) {
            $product->load(['category', 'images', 'colors', 'sizes']);
        }
        return $product;
    }

    public function productTopSelling(): Collection
    {
        $products =  Product::where('sales_number', '>=', 500)
            ->orderBy('sales_number', 'desc') // Urutkan dari tertinggi ke terendah
            ->get();

        $products->load(['category', 'images', 'colors', 'sizes']);
        return $products;
    }

    public function productNewIn(): Collection
    {
        // Define a start date, e.g., products created in the last 30 days
        $startDate = now()->subDays(30);

        $products = Product::where('created_at', '>=', $startDate)->latest()
            ->get();

        $products->load(['category', 'images', 'colors', 'sizes']);
        return $products;
    }

    public function toggleFavorite(int $productId, int $userId): array
    {

        $user = User::findOrFail($userId);

        $toggleResult = $user->favorites()->toggle($productId);

        return $toggleResult;
    }

    public function getFavoriteProducts(int $userId): Collection
    {
        $productFavorites = Product::whereHas('favoriteBy', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        $productFavorites->load(['category', 'images', 'colors', 'sizes']);


        return $productFavorites;
    }

    public function getProductsByIdCategory(int $categoryId): Collection
    {
        $query = Product::query();
        $products = $query->where('category_id', $categoryId)->latest()->get();

        $products->load(['category', 'images', 'colors', 'sizes']);

        return $products;
    }

    public function getProductsByTitle(string $title): Collection
    {
        $query = Product::query();
        $products = $query->where('title', 'LIKE', '%' . $title . '%')->latest()->get();

        $products->load(['category', 'images', 'colors', 'sizes']);

        return $products;
    }
}
