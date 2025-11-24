<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;

class ProductController extends Controller
{

    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/get-top-selling",
     *     tags={"Product"},
     *     summary="Get top selling products",
     *     description="Mengambil daftar produk terlaris berdasarkan penjualan.",
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
    public function getTopSelling(Request $request)
    {
        $userId = $request->user()->id;
        try {
            $products = $this->productService->productTopSelling($userId);

            return response()->json([
                'message' => 'Success',
                'results' => ProductResource::collection($products)
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching top selling products: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error: Failed to fetch top selling products.',
                'error_code' => $e->getCode()
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/get-new-in",
     *     tags={"Product"},
     *     summary="Get new in products",
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

        try {
            $products = $this->productService->productNewIn();

            return response()->json([
                'message' => 'Success',
                'results' => ProductResource::collection($products)
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching new in products: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error: Failed to fetch new in products.',
                'error_code' => $e->getCode()
            ], 500);
        }
    }



    /**
     * @OA\Post(
     *     path="/toggle-favorite",
     *     tags={"Product"},
     *     summary="Toggle favorite status for a product",
     *     description="Menandai atau menghapus tanda favorit pada produk tertentu untuk user yang sedang login.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID produk yang akan ditandai atau dihapus dari favorit")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengubah status favorit produk",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil ditambahkan ke favorit."),
     *             @OA\Property(property="status", type="boolean", example=true, description="Status favorit produk setelah toggle (true: ditandai sebagai favorit, false: dihapus dari favorit)")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error: The product_id field is required.")
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
        $userId = $request->user()->id;
        $productId = $request->input('product_id');

        if (empty($productId)) {
            return response()->json([
                'message' => 'Validation Error: The product_id field is required.'
            ], 422);
        }

        try {
            $result = $this->productService->toggleFavorite($productId, $userId);

            if (isset($result['attached']) && count($result['attached']) > 0) {
                return response()->json([
                    'message' => 'Produk berhasil ditambahkan ke favorit.',
                    'status' => true
                ], 201);
            } elseif (isset($result['detached']) && count($result['detached']) > 0) {
                return response()->json([
                    'message' => 'Produk berhasil dihapus dari favorit.',
                    'status' => false
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Tidak ada perubahan pada status favorit produk.',
                    'status' => null
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error("Error toggling favorite status: " . $e->getMessage(), [
                'product_id' => $productId,
                'user_id' => $userId,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error: Failed to toggle favorite status.',
                'error_code' => $e->getCode()
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/get-favorite-products",
     *     tags={"Product"},
     *     summary="Get favorite products of the authenticated user",
     *     description="Mengambil daftar produk favorit dari user yang sedang login.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar produk favorit",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get Favorites Products Successfully"),
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

        try {
            $products = $this->productService->getFavoriteProducts($userId);

            return response()->json([
                'message' => 'Get Favorites Products Successfuly',
                'results' => ProductResource::collection($products)
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching favorite products: " . $e->getMessage(), [
                'user_id' => $userId,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error: Failed to fetch favorite products.',
                'error_code' => $e->getCode()
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/get-products-byid-category",
     *     tags={"Product"},
     *     summary="Get products by category ID",
     *     description="Mengambil daftar produk berdasarkan kategori yang dikirim melalui query parameter.",
     *     security={{"bearerAuth": {}}},
     *     *
     *     @OA\Parameter(
     *         name="query_params",
     *         in="query",
     *         required=true,
     *         description="ID kategori produk",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Produk ditemukan",
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
    public function getProductsByIdCategory(Request $request)
    {
        $categoryId = $request->query('query_params');
        try {
            $products = $this->productService->getProductsByIdCategory($categoryId);

            return response()->json([
                'message' => 'Get Products Successfuly',
                'results' => ProductResource::collection($products)
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching products by category ID: " . $e->getMessage(), [
                'category_id' => $categoryId,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error: Failed to fetch products by category ID.',
                'error_code' => $e->getCode()
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/get-products-by-title",
     *     tags={"Product"},
     *     summary="Get products by title",
     *     description="Mengambil daftar produk berdasarkan judul yang dikirim melalui query parameter.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="query_params",
     *         in="query",
     *         required=true,
     *         description="Judul produk",
     *         @OA\Schema(type="string", example="Sneakers")
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
        try {
            $products = $this->productService->getProductsByTitle($title);

            return response()->json([
                'message' => 'Get Products Successfully',
                'results' => ProductResource::collection($products)
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching products by title: " . $e->getMessage(), [
                'title' => $title,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error: Failed to fetch products by title.',
                'error_code' => $e->getCode()
            ], 500);
        }
    }
}
