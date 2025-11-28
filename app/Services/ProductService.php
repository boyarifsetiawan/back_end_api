<?php

namespace App\Services;

use App\Interface\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProductService
{

    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function getProductById(int $id)
    {
        try {
            $product = $this->productRepository->getProductByid($id);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        return $product;
    }

    public function productTopSelling()
    {
        $cacheKey = 'top_selling_products';
        $ttlInSeconds = 60 * 60;

        $products = Cache::remember($cacheKey, $ttlInSeconds, function () {

            return $this->productRepository->productTopSelling();
        });

        return $products;
    }

    public function productNewIn()
    {
        $cacheKey = 'new_in_products';
        $ttlInSeconds = 60 * 60;
        $products = Cache::remember($cacheKey, $ttlInSeconds, function () {

            return $this->productRepository->productNewIn();
        });

        return $products;
    }

    public function toggleFavorite(int $productId, int $userId)
    {
        try {
            $result = $this->productRepository->toggleFavorite($productId, $userId);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        return $result;
    }

    public function getFavoriteProducts(int $userId)
    {
        $cacheKey = 'favorite_products_' . $userId;
        $ttlInSeconds = 60 * 60;
        $products = Cache::remember($cacheKey, $ttlInSeconds, function () use ($userId) {

            return $this->productRepository->getFavoriteProducts($userId);
        });

        return $products;
    }

    public function getProductsByIdCategory(int $categoryId)
    {
        $cacheKey = 'products_by_category_' . $categoryId;
        $ttlInSeconds = 60 * 60;
        $products = Cache::remember($cacheKey, $ttlInSeconds, function () use ($categoryId) {

            return $this->productRepository->getProductsByIdCategory($categoryId);
        });

        return $products;
    }

    public function getProductsByTitle(string $title)
    {
        try {
            $products = $this->productRepository->getProductsByTitle($title);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        return $products;
    }
}
