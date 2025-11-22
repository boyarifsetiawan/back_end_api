<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    protected $service;
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/order-registration",
     *     tags={"Order"},
     *     summary="Register a new order",
     *     description="Mendaftarkan pesanan baru berdasarkan data yang dikirimkan user.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order registration payload",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", example=12),
     *             @OA\Property(property="item_count", type="integer", example=3),
     *             @OA\Property(property="total_price", type="number", format="float", example=450000),
     *             @OA\Property(property="shipping_address", type="string", example="Jl. Merdeka No. 123, Jakarta"),
     *
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=5),
     *                     @OA\Property(property="title", type="string", example="Nike Air Max 97"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="color", type="string", example="Black"),
     *                     @OA\Property(property="size", type="string", example="Xl"),
     *                     @OA\Property(property="product_price", type="number", format="float", example=225000),
     *                     @OA\Property(property="total_price", type="number", format="float", example=450000),
     *                     @OA\Property(property="image", type="string", example="https://cdn.example.com/products/airmax.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The Products was ordered successfuly")
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
    public function orderRegistration(Request $request)
    {

        $userId = $request->user()->id;

        $this->service->createOrder($request, $userId);

        return response()->json([
            'message' => 'The Products was ordered successfuly',
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/get-orders",
     *     tags={"Order"},
     *     summary="Get user's orders",
     *     description="Mengambil semua pesanan milik user yang sedang login, termasuk statuses dan orderedProducts.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar pesanan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get orders successfuly"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 description="Daftar semua pesanan user",
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="user_id", type="integer", example=3),
     *                     @OA\Property(property="item_count", type="integer", example=5),
     *                     @OA\Property(property="total_price", type="number", format="float", example=450000),
     *                     @OA\Property(property="shipping_address", type="string", example="Jl. Merdeka No. 123, Jakarta"),
     *                     @OA\Property(property="status", type="string", example="processing"),
     *
     *                     @OA\Property(
     *                         property="statuses",
     *                         type="array",
     *                         description="Riwayat status order",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="status", type="string", example="processing"),
     *                             @OA\Property(property="created_at", type="string", example="2025-01-20 14:23:00")
     *                         )
     *                     ),
     *
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         description="Produk yang dipesan",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="product_id", type="integer", example=12),
     *                             @OA\Property(property="title", type="string", example="Nike Air Max 270"),
     *                             @OA\Property(property="quantity", type="integer", example=2),
     *                             @OA\Property(property="color", type="string", example="Black"),
     *                             @OA\Property(property="size", type="string", example="Xl"),
     *                             @OA\Property(property="product_price", type="number", format="float", example=150000),
     *                             @OA\Property(property="total_price", type="number", format="float", example=300000),
     *                             @OA\Property(property="image", type="string", example="https://example.com/image.jpg")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User belum login"
     *     )
     * )
     */
    public function getOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->get();
        $orders->load(['statuses', 'orderedProducts']);
        return response()->json([
            'message' => 'Get orders successfuly',
            'results' => OrderResource::collection($orders)
        ], 201);
    }
}
