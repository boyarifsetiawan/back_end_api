<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Requests\AddToCartRequest;

class CartController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    /**
     * @OA\Post(
     *     path="/add-to-cart",
     *     tags={"Cart"},
     *     summary="Add a product to the cart",
     *     description="Menambahkan produk ke keranjang belanja user yang sedang login.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil ditambahkan ke keranjang",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil ditambahkan ke keranjang."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CartResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error.")
     *         )
     *     )
     * )
     */
    public function addToCart(AddToCartRequest $request)
    {
        try {
            $userId = $request->user()->id;
            $cartData = $request->validated();
            $products = $this->cartService->addToCart($cartData, $userId);
            return response([
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'results' => CartResource::collection($products),
            ], 201);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/cart",
     *     tags={"Cart"},
     *     summary="Get all product carts",
     *     description="Mengambil semua product di cart berdasarkan user id.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get product carts successfully."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CartResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error.")
     *         )
     *     )
     * )
     */
    public function getProductCarts(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $productCarts = $this->cartService->getProductCarts($userId);
            return response([
                'message' => 'Get product carts successfully.',
                'results' => CartResource::collection($productCarts),
            ], 200);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/remove-cart-product",
     *     tags={"Cart"},
     *     summary="Remove a product from the cart",
     *     description="Menghapus produk dari keranjang belanja berdasarkan cart ID.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="cart_id",
     *         in="query",
     *         description="ID of the cart item to remove",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil dihapus dari keranjang",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product has been removed from cart successfully."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CartResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid cart ID.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error.")
     *         )
     *     )
     * )
     */
    public function removeCartProduct(Request $request)
    {
        $userId = $request->user()->id;
        $cartId = $request->query('query_params');
        if (!$cartId) {
            return response(['message' => 'Invalid cart ID.'], 400);
        }
        try {
            $productCarts = $this->cartService->removeCartProduct($cartId, $userId);
            return response([
                'message' => 'Product has been removed from cart successfully.',
                'results' => CartResource::collection($productCarts),
            ], 200);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
