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
     *     description="Menambahkan produk ke dalam keranjang belanja user yang sedang login.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","product_title","product_quantity","product_color","product_size","product_price","product_image","total_price"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="product_title", type="string", example="Sample Product"),
     *             @OA\Property(property="product_quantity", type="integer", example=2),
     *             @OA\Property(property="product_color", type="string", example="Red"),
     *             @OA\Property(property="product_size", type="string", example="M"),
     *             @OA\Property(property="product_price", type="number", format="float", example=99.99),
     *             @OA\Property(property="product_image", type="string", example="http://example.com/image.jpg"),
     *             @OA\Property(property="total_price", type="number", format="float", example=199.98)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil ditambahkan ke keranjang",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil ditambahkan ke keranjang."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Cart")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
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
     *     path="/get-product-carts",
     *     tags={"Cart"},
     *     summary="Get products in the authenticated user's cart",
     *     description="Mengambil daftar produk yang ada di keranjang belanja user yang sedang login.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar produk di keranjang",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get product carts successfully."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Cart")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
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
     *     description="Menghapus produk dari keranjang berdasarkan cart ID yang diberikan.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="query_params",
     *         in="query",
     *         required=true,
     *         description="ID unik cart yang ingin dihapus",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil dihapus dari keranjang",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product has been removed from cart successfully."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Cart")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function removeCartProduct(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $cartId = $request->query('query_params');
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
