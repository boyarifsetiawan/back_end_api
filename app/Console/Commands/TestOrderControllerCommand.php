<?php

namespace App\Console\Commands;

use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Console\Command;

class TestOrderControllerCommand extends Command
{
    protected $signature = 'test:order-controller
        {--user_id=1 : ID user}
        {--address="Jl. Testing No. 123" : Shipping address}';

    protected $description = 'Test create order using OrderService with mock payload';

    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $this->info("Running controller-like test using OrderService...");

        // Dummy products
        $products = [
            ['product_id' => 1, 'quantity' => 2, 'price' => 110000, 'color' => 'Red', 'size' => 'L'],
            ['product_id' => 2, 'quantity' => 1, 'price' => 95000, 'color' => 'Blue', 'size' => 'M'],
        ];

        // Build payload (sama seperti Request)
        $payload = [
            'user_id' => $this->option('user_id'),
            'shipping_address' => $this->option('address'),
            'products' => $products
        ];

        // Call service
        $order = $this->service->createOrder($payload)->load(['products.product', 'statuses', 'user']);

        $this->info("✔ Order created!");
        $this->line("Order Code: " . $order->id);
        $this->line("Item Count: " . $order->item_count);
        $this->line("Total: " . $order->total_price);

        // Show fresh with relationships
        $this->newLine();
        $this->info("Order detail:");

        $json = (new OrderResource($order))->response()->getData(true);

        $this->line(json_encode($json, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
