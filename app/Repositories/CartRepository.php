<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Interface\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function addToCart(array $cart, int $userId): Collection
    {
        Cart::create([
            'user_id' => $userId,
            'product_id' => $cart['product_id'],
            'product_title' => $cart['product_title'],
            'product_quantity' => $cart['product_quantity'],
            'product_color' => $cart['product_color'],
            'product_size' => $cart['product_size'],
            'product_price' => $cart['product_price'],
            'product_image' => $cart['product_image'],
            'total_price' => $cart['total_price'],
        ]);

        return Cart::all();
    }

    public function getProductCarts(int $userId): Collection
    {
        return Cart::where('user_id', $userId)->get();
    }

    public function removeCartProduct(int $cartId, int $userId): Collection
    {
        Cart::where('id', $cartId)->where('user_id', $userId)->delete();

        return Cart::where('user_id', $userId)->get();
    }
}
