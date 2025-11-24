<?php

namespace App\Services;

use App\Interface\CartRepositoryInterface;


class CartService
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Add product to cart
     *
     * @param array $cart
     * @param int $userId
     * @return mixed
     * @throws \Exception
     */
    public function addToCart(array $cart, int $userId)
    {
        try {
            return $this->cartRepository->addToCart($cart, $userId);
        } catch (\Throwable $th) {
            throw new \Exception('Failed to add product to cart: ' . $th->getMessage());
        }
    }

    /**
     * Get products in cart
     *
     * @param int $userId
     * @return mixed
     * @throws \Exception
     */
    public function getProductCarts(int $userId)
    {
        try {
            return $this->cartRepository->getProductCarts($userId);
        } catch (\Throwable $th) {
            throw new \Exception('Failed to get cart products: ' . $th->getMessage());
        }
    }

    /**
     * Remove product from cart
     *
     * @param int $cartId
     * @param int $userId
     * @return mixed
     * @throws \Exception
     */
    public function removeCartProduct(int $cartId, int $userId)
    {
        try {
            return $this->cartRepository->removeCartProduct($cartId, $userId);
        } catch (\Throwable $th) {
            throw new \Exception('Failed to remove product from cart: ' . $th->getMessage());
        }
    }
}
