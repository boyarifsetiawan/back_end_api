<?php


namespace App\Repositories;

use App\Models\User;
use App\Models\Product;

class ProductRepository
{

    public function productTopSelling()
    {
        $products =  Product::where('sales_number', '>=', 500)
            ->orderBy('sales_number', 'desc') // Urutkan dari tertinggi ke terendah
            ->get();

        $products->load(['category', 'images', 'colors', 'sizes']);
        // $products->load(['colors']);
        // $products->load(['sizes']);
        // dd($products);
        return $products;
    }

    public function productNewIn()
    {
        $startDate = '2025-11-15';

        $products = Product::where('created_at', '>=', $startDate)->latest()
            ->get();

        $products->load(['category', 'images', 'colors', 'sizes']);
        // $products->load(['colors']);
        // $products->load(['sizes']);
        // dd($products);
        return $products;
    }

    public function toggleFavorite($productId,  $userId)
    {

        $user = User::findOrFail($userId);

        $toggleResult = $user->favorites()->toggle($productId);

        return $toggleResult;
    }

    public function getFavoriteProducts($userId)
    {
        $productFavorites = Product::whereHas('favoriteBy', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        $productFavorites->load(['category', 'images', 'colors', 'sizes']);


        return $productFavorites;
    }

    public function getProductsByIdCategory($categoryId)
    {
        $query = Product::query();
        $products = $query->where('category_id', $categoryId)->latest()->get();

        $products->load(['category', 'images', 'colors', 'sizes']);

        return $products;
    }

    public function getProductsByIdTitle($title)
    {
        $query = Product::query();
        $products = $query->where('title', 'LIKE', '%' . $title . '%')->latest()->get();

        $products->load(['category', 'images', 'colors', 'sizes']);

        return $products;
    }


    public function getAll(array $filters = [])
    {
        return Product::with(['images', 'sizes', 'colors', 'category'])->get();
    }

    public function findById(string $id)
    {
        return Product::with(['images', 'sizes', 'colors', 'category'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(string $id, array $data)
    {
        $p = Product::findOrFail($id);
        $p->update($data);
        return $p;
    }

    public function delete(string $id)
    {
        $p = Product::findOrFail($id);
        return $p->delete();
    }
}
