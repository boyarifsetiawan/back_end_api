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
     *      path="/get-top-selling",
     *      operationId="getTopSellingProducts",
     *      tags={"Products"},
     *      summary="Get top selling products for the authenticated user",
     *      description="Returns a list of top selling products for the authenticated user.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Success"),
     *              @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Internal Server Error: Failed to fetch top selling products."),
     *              @OA\Property(property="error_code", type="integer", example=500)
     *          )
     *      )
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
     *      path="/get-new-in",
     *      operationId="getNewInProducts",
     *      tags={"Products"},
     *      summary="Get new in products",
     *      description="Returns a list of new in products.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Success"),
     *              @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Internal Server Error: Failed to fetch new in products."),
     *              @OA\Property(property="error_code", type="integer", example=500)
     *          )
     *      )
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
     *      path="/toggle-favorite",
     *      operationId="toggleFavoriteProduct",
     *      tags={"Products"},
     *      summary="Toggle a product's favorite status for the authenticated user",
     *      description="Toggles the favorite status of a product for the authenticated user. Returns success message and status.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"product_id"},
     *              @OA\Property(property="product_id", type="integer", example=1, description="ID of the product to toggle favorite status")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation - Product removed from favorites",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Produk berhasil dihapus dari favorit."),
     *              @OA\Property(property="status", type="boolean", example=false)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation - Product added to favorites",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Produk berhasil ditambahkan ke favorit."),
     *              @OA\Property(property="status", type="boolean", example=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Validation Error: The product_id field is required.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Internal Server Error: Failed to toggle favorite status."),
     *              @OA\Property(property="error_code", type="integer", example=500)
     *          )
     *      )
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
     *      path="/get-favorite-products",
     *      operationId="getFavoriteProducts",
     *      tags={"Products"},
     *      summary="Get favorite products for the authenticated user",
     *      description="Returns a list of favorite products for the authenticated user.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Get Favorites Products Successfuly"),
     *              @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Internal Server Error: Failed to fetch favorite products."),
     *              @OA\Property(property="error_code", type="integer", example=500)
     *          )
     *      )
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
     *      path="/get-products-byid-category",
     *      operationId="getProductsByIdCategory",
     *      tags={"Products"},
     *      summary="Get products by category ID",
     *      description="Returns a list of products filtered by category ID.",
     *      @OA\Parameter(
     *          name="query_params",
     *          in="query",
     *          description="Category ID to filter products",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Get Products Successfuly"),
     *              @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Internal Server Error: Failed to fetch products by category ID."),
     *              @OA\Property(property="error_code", type="integer", example=500)
     *          )
     *      )
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
     *      path="/get-products-by-title",
     *      operationId="getProductsByTitle",
     *      tags={"Products"},
     *      summary="Get products by title",
     *      description="Returns a list of products filtered by title.",
     *      @OA\Parameter(
     *          name="query_params",
     *          in="query",
     *          description="Title to filter products",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Get Products Successfully"),
     *              @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Internal Server Error: Failed to fetch products by title."),
     *              @OA\Property(property="error_code", type="integer", example=500)
     *          )
     *      )
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
