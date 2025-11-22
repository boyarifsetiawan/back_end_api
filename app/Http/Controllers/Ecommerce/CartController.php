<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Requests\AddToCartRequest;
use App\Repositories\CartRepository;

class CartController extends Controller
{
    protected $repo;
    public function __construct(CartRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @OA\Post(
     *      path="/add-to-cart",
     *      tags={"Cart"},
     *      summary="Tambahkan produk baru ke keranjang belanja",
     *      description="Menambahkan produk ke keranjang belanja pengguna saat ini atau memperbarui kuantitas jika produk sudah ada.",
     *      security={{"bearerAuth": {}}},
     *          @OA\RequestBody(
     *              required=true,
     *              description="Data yang diperlukan untuk menambahkan produk ke keranjang.",
     *                  @OA\JsonContent(
     *                      required={"product_id", "quantity"},
     *                          @OA\Property(property="product_id", type="integer", example=45, description="ID unik produk"),
     *                          @OA\Property(property="quantity", type="integer", example=2, description="Jumlah produk yang ingin ditambahkan"),
     *                          @OA\Property(property="color", type="string", nullable=true, example="Merah", description="Varian warna produk (opsional)"),
     *                          @OA\Property(property="size", type="string", nullable=true, example="L", description="Varian ukuran produk (opsional)")
     *                  )
     *           ),
     *          @OA\Response(
     *              response=201,
     *              description="Produk berhasil ditambahkan atau diperbarui di keranjang",
     *                  @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Produk berhasil ditambahkan ke keranjang."),
     *                      @OA\Property(
     *                          property="results",
     *                          type="array",
     *                          description="Daftar item keranjang yang diperbarui (menggunakan CartResource)",
     *                               @OA\Items(ref="#/components/schemas/Cart")
     *                   )
     *               )
     *           ),
     *          @OA\Response(
     *              response=401,
     *              description="Unauthorized - User belum login",
     *                  @OA\JsonContent(@OA\Property(property="message", type="string", example="Unauthenticated."))
     *            ),
     *          @OA\Response(
     *              response=422,
     *              description="Validation error",
     *                  @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The given data was invalid."),
     *                  @OA\Property(property="errors", type="object", description="Detail kesalahan validasi")
     *                  )
     *           )
     * )
     *
     * Handle user request to add a product to the cart.
     *
     * @param \App\Http\Requests\AddToCartRequest $request Incoming request containing product details.
     * @return \Illuminate\Http\JsonResponse JSON response with cart items on success.
     */
    public function addToCart(AddToCartRequest $request)
    {
        $validatedData = $request->validated();
        $userId = $request->user()->id;

        $products = $this->repo->addToCart($validatedData, $userId);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'results' => CartResource::collection($products),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/get-product-carts",
     *     tags={"Cart"},
     *     summary="Get all product carts",
     *     description="Mengambil semua data cart dan mengembalikannya dalam bentuk CartResource collection.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar cart",
     *         @OA\JsonContent(
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
    public function getProductCarts()
    {
        return response(['results' => CartResource::collection(Cart::all())], 200);
    }


    /**
     *@OA\Delete(
     *     path="/remove-cart-product",
     *     tags={"Cart"},
     *     summary="Hapus satu item dari keranjang belanja",
     *     description="Menghapus item keranjang berdasarkan ID produk atau ID item keranjang yang diberikan sebagai query parameter. Setelah dihapus, mengembalikan daftar item keranjang yang tersisa.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="query_params",
     *         in="query",
     *         required=true,
     *         description="ID item keranjang (Cart ID) atau ID produk (Product ID) yang akan dihapus.",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Item berhasil dihapus dari keranjang",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil dihapus dari keranjang."),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 description="Daftar item keranjang yang tersisa (menggunakan CartResource)",
     *                      @OA\Items(ref="#/components/schemas/Cart")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User belum login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Item keranjang tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Item keranjang tidak ditemukan.")
     *         )
     *     )
     * )
     *
     * Handles the request to remove a specific product/item from the user's cart.
     *
     * @param \Illuminate\Http\Request $request Incoming request containing the ID in query_params.
     * @return \Illuminate\Http\JsonResponse JSON response with remaining cart items.
     */
    public function removeCartProduct(Request $request)
    {
        $productId = $request->query('query_params');
        $products = $this->repo->removeCart($productId);
        return response([
            'message' => 'Produk berhasil dihapus dari keranjang.',
            'results' => CartResource::collection($products),
        ], 200);
    }
}
