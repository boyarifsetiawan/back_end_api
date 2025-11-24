<?php

namespace App\Interface;

use App\Models\Cart;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    /**
     * Add product to cart
     *
     * @param array $cart
     * @param integer $userId
     * @return Collection
     */
    public function addToCart(array $cart, int $userId): Collection;

    /**
     * Get products in cart
     *
     * @param integer $userId
     * @return Collection
     */
    public function getProductCarts(int $userId): Collection;

    /**
     * Remove product from cart
     *
     * @param integer $cartId
     * @param integer $userId
     * @return Collection
     */
    public function removeCartProduct(int $cartId, int $userId): Collection;
}
