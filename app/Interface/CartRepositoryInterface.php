<?php

namespace App\Interface;

use App\Models\Cart;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function addToCart(array $cart, int $userId): Collection;
    public function getProductCarts(int $userId): Collection;
    public function removeCartProduct(int $cartId, int $userId): Collection;
}
