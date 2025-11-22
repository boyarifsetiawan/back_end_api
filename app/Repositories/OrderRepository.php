<?php

namespace App\Repositories;

use App\Models\Order;


class OrderRepository
{
    public function index()
    {
        return Order::with(['user', 'products.product', 'statuses'])->get();
    }

    public function find($id)
    {
        return Order::with(['user', 'products.product', 'statuses'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update($id, array $data)
    {
        $o = Order::findOrFail($id);
        $o->update($data);
        return $$o;
    }
}
