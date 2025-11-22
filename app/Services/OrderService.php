<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $repo;

    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Creates a new order with associated ordered products and sets the initial order status to pending.
     *
     * @param array $payload
     * @return Order
     */
    public function createOrder(Request $request, $userId): Order
    {
        // dd($request->itemCount);
        DB::beginTransaction();


        $order = Order::create([
            'user_id' => $userId,
            'code' => 'ORD-' . time() . rand(100, 999),
            'item_count' => $request->itemCount,
            'total_price' => $request->totalPrice,
            'shipping_address' => $request->shippingAddress,
        ]);
        // dd($order->orderedProducts());

        OrderStatus::create([
            'order_id' => $order->id,
            'title' => 'PAID',
            'done' => true,
        ]);

        $cartItemIdsToDelete = [];

        foreach ($request->products as $p) {

            $orderProduct = new OrderedProduct([
                'order_id' => $order->id,
                'product_id' => $p['productId'],
                'title' => $p['productTitle'],
                'quantity' => $p['productQuantity'],
                'color' => $p['productColor'],
                'size' => $p['productSize'],
                'product_price' => $p['productPrice'],
                'total_price' => $p['totalPrice'],
                'image' => $p['productImage'],
            ]);

            $orderProduct->save();

            if (isset($p->id)) {
                $cartItemIdsToDelete[] = $p->id;
            }
        }

        if (!empty($cartItemIdsToDelete)) {
            Cart::whereIn('id', $cartItemIdsToDelete)->where('user_id', $userId)
                ->delete();
        }

        DB::commit();

        return $order;

        // $itemCount = array_sum(array_map(function ($p) {
        //     return $p['quantity'];
        // }, $payload['products']));
        // $total = array_sum(array_map(function ($p) {
        //     return $p['quantity'] * $p['price'];
        // }, $payload['products']));


        // $order = $this->repo->create([
        //     'user_id' => $payload['user_id'],
        //     'shipping_address' => $payload['shipping_address'],
        //     'item_count' => $itemCount,
        //     'total_price' => $total,
        //     'created_date' => now()
        // ]);

        // foreach ($payload['products'] as $p) {
        //     OrderedProduct::create([
        //         'order_id' => $order->id,
        //         'product_id' => $p['product_id'],
        //         'quantity' => $p['quantity'],
        //         'price' => $p['price'],
        //         'color' => $p['color'] ?? null,
        //         'size' => $p['size'] ?? null
        //     ]);
        // }

        // // initialstatus pending
        // OrderStatus::create([
        //     'order_id' => $order->id,
        //     'status' => 'pending',
        //     'created_at' => now()
        // ]);

        // return $order;
    }

    public function markPaid($id)
    {
        $order = $this->repo->find($id);
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'paid',
            'created_date' => now()
        ]);

        return $order;
    }
}
