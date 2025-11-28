<?php

namespace App\Interface;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{

    /**
     * @param int $id
     * @return Product|null
     */
    public function getProductByid(int $id): ?Product;

    /**
     * @return Collection
     */
    public function productTopSelling(): Collection;

    /**
     * @return Collection
     */
    public function productNewIn(): Collection;

    /**
     * @param int $productId
     * @param int $userId
     * @return array
     */
    public function toggleFavorite(int $productId,  int $userId): array;

    /**
     * @param int $userId
     * @return Collection
     */
    public function getFavoriteProducts(int $userId): Collection;

    /**
     * @param int $categoryId
     * @return Collection
     */
    public function getProductsByIdCategory(int $categoryId): Collection;

    /**
     * @param string $title
     * @return Collection
     */
    public function getProductsByTitle(string $title): Collection;
}
