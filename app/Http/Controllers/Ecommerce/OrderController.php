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
     * Creates New Order
     * @OA\Post(
     *     path="/order-registration",
     *     summary="Register a new order",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="shipping_address_id", type="integer", example="1"),
     *             @OA\Property(property="billing_address_id", type="integer", example="2"),
     *             @OA\Property(property="payment_method", type="string", example="credit_card"),
     *             @OA\Property(property="products", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="quantity", type="integer", example="2")
     *              )),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The Products was ordered successfuly")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
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
     * Get Orders
     * @OA\Get(
     *     path="/get-orders",
     *     summary="Get all orders for the authenticated user",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get orders successfuly"),
     *             @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/OrderResource"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
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
