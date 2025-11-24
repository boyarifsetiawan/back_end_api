<?php

namespace App\Interface;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getAllProducts(): Collection;
    public function getProductByid(int $id): ?Product;
    public function productTopSelling(): Collection;
    public function productNewIn(): Collection;
    public function toggleFavorite(int $productId,  int $userId): array;
    public function getFavoriteProducts(int $userId): Collection;
    public function getProductsByIdCategory(int $categoryId): Collection;
    public function getProductsByTitle(string $title): Collection;
}
