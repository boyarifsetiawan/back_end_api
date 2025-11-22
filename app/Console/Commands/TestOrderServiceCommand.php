<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Console\Command;

class TestOrderServiceCommand extends Command
{
    protected $signature = 'test:order';
    protected $description = 'Create a dummy order using OrderService';

    protected $service;

    public function __construct(OrderService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $this->info("🔧 Generating dummy order...");

        // pilih user random
        $user = User::inRandomOrder()->first();

        // pilih 1–3 produk random
        $products = Product::inRandomOrder()->take(rand(2, 3))->get();

        $payloadProducts = [];

        foreach ($products as $p) {
            $payloadProducts[] = [
                'product_id' => $p->id,
                'quantity' => rand(1, 3),
                'price' => $p->price,
                'color' => $p->colors()->exists()
                    ? $p->colors()->inRandomOrder()->first()->title
                    : null,
                'size' => $p->sizes()->exists()
                    ? $p->sizes()->inRandomOrder()->first()->size
                    : null,
            ];
        }

        $payload = [
            'user_id' => $user->id,
            'shipping_address' => 'Test Address',
            'products' => $payloadProducts
        ];

        $order = $this->service->createOrder($payload);

        $this->info("✅ Order created successfully!");
        $this->info("Order Code: " . $order->id);
        $this->info("Item Count: " . $order->item_count);
        $this->info("Total Price: " . $order->total_price);

        return 0;
    }
}
