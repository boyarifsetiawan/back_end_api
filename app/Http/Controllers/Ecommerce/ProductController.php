<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{

    protected $repo;
    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @OA\Get(
     *     path="/get-top-selling",
     *     tags={"Product"},
     *     summary="Get top selling products",
     *     description="Mengambil daftar produk dengan penjualan terbanyak.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar produk terlaris",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
    public function getTopSelling()
    {
        return response([
            'message' => 'Success',
            'results' => ProductResource::collection($this->repo->productTopSelling())

        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/get-new-in",
     *     tags={"Product"},
     *     summary="Get newly added products",
     *     description="Mengambil daftar produk terbaru yang baru saja ditambahkan.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar produk terbaru",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
    public function getNewIn()
    {

        return response([
            'message' => 'Success',
            'results' => ProductResource::collection($this->repo->productNewIn())

        ], 200);
    }



    /**
     * @OA\Post(
     *     path="/toggle-favorite",
     *     tags={"Product"},
     *     summary="Toggle product favorite status",
     *     description="Menambahkan atau menghapus produk dari daftar favorit user berdasarkan product_id.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payload untuk toggle favorit",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_id", type="integer", example=7)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil ditambahkan ke favorit",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil ditambahkan ke favorit."),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil dihapus dari favorit / tidak ada perubahan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil dihapus dari favorit."),
     *             @OA\Property(property="status", type="boolean", example=false)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The product_id field is required.")
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
    public function toggleFavorite(Request $request)
    {
        // 1. Validasi Input (opsional, tapi disarankan)
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = $request->user()->id;
        $productId = $validated['product_id'];

        $toggleResult = $this->repo->toggleFavorite($productId, $userId);

        if (!empty($toggleResult['attached'])) {

            $message = 'Produk berhasil ditambahkan ke favorit.';
            $status = true;
            $statusCode = 201; // Created

        }
        // Jika 'detached' tidak kosong, berarti produk DIHAPUS dari favorit.
        elseif (!empty($toggleResult['detached'])) {

            $message = 'Produk berhasil dihapus dari favorit.';
            $status = false;
            $statusCode = 200; // OK

        } else {
            // Kasus jarang, jika tidak ada perubahan
            $message = 'Tidak ada perubahan pada daftar favorit.';
            $statusCode = 200;
        }

        // 6. Kembalikan Response JSON
        return response()->json([
            'message' => $message,
            'status' => $status
        ], $statusCode);
    }


    /**
     * @OA\Get(
     *     path="/get-favorite-products",
     *     tags={"Product"},
     *     summary="Get all favorited products",
     *     description="Mengambil semua produk yang telah ditandai sebagai favorit oleh user.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar produk favorit",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get Favorites Products Successfuly"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
    public function getFavoriteProducts(Request $request)
    {
        $userId = $request->user()->id;
        $products = $this->repo->getFavoriteProducts($userId);

        return response()->json([
            'message' => 'Get Favorites Products Successfuly',
            'results' => ProductResource::collection($products)
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/get-products-byid-category",
     *     tags={"Product"},
     *     summary="Get products by category ID",
     *     description="Mengambil daftar produk berdasarkan ID kategori yang dikirimkan melalui query parameter.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="query_params",
     *         in="query",
     *         required=true,
     *         description="ID kategori untuk mengambil daftar produk",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar produk",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get Products Successfuly"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
    public function getProductsByIdCategory(Request $request)
    {
        $categoryId = $request->query('query_params');
        $products = $this->repo->getProductsByIdCategory($categoryId);

        return response()->json([
            'message' => 'Get Products Successfuly',
            'results' => ProductResource::collection($products)
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/get-products-by-title",
     *     tags={"Product"},
     *     summary="Get products by title keyword",
     *     description="Mengambil daftar produk berdasarkan pencarian judul (title) yang dikirim melalui query parameter.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="query_params",
     *         in="query",
     *         required=true,
     *         description="Keyword atau title produk yang ingin dicari",
     *         @OA\Schema(type="string", example="Air Jordan")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Produk ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get Products Successfully"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
    public function getProductsByTitle(Request $request)
    {
        $title = $request->query('query_params');
        $products = $this->repo->getProductsByIdTitle($title);

        if (isEmpty($products)) {
            return response()->json([
                'message' => 'Product Not Found',
                'results' => ProductResource::collection($products)
            ], 404);
        }

        return response()->json([
            'message' => 'Get Products Successfuly',
            'results' => ProductResource::collection($products)
        ], 200);
    }
}
