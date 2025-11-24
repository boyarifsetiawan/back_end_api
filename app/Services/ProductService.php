<?php

namespace App\Services;

use App\Interface\ProductRepositoryInterface;

class ProductService
{
    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        try {
            $products = $this->productRepository->getAllProducts();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        return $products;
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
        try {
            $products = $this->productRepository->productTopSelling();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        return $products;
    }

    public function productNewIn()
    {
        try {
            $products = $this->productRepository->productNewIn();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
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
        try {
            $products = $this->productRepository->getFavoriteProducts($userId);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        return $products;
    }

    public function getProductsByIdCategory(int $categoryId)
    {
        try {
            $products = $this->productRepository->getProductsByIdCategory($categoryId);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
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
