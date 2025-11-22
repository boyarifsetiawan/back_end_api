<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartRepository
{

    public function addToCart($validatedData, $userId)
    {
        Cart::create([
            'user_id' => $userId,
            'product_id' => $validatedData['product_id'],
            'product_title' => $validatedData['product_title'],
            'product_quantity' => $validatedData['product_quantity'],
            'product_color' => $validatedData['product_color'],
            'product_size' => $validatedData['product_size'],
            'product_price' => $validatedData['product_price'],
            'product_image' => $validatedData['product_image'],
            'total_price' => $validatedData['total_price'],
        ]);
        $query = Cart::query();
        $products = $query->latest()->get();

        return $products;
    }

    public function removeCart($productId)
    {
        $query = Cart::query();
        $product = $query->find($productId);
        $product->delete();

        return Cart::all();
    }
}
