<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderStatus;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\OrderedProduct;
use Illuminate\Database\Seeder;
use Database\Seeders\SongSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    // $keywords = [
    //         'hoodie',
    //         'bag',
    //         'shoes',
    //         'jacket',
    //         'accessories',
    //         'shorts'
    //     ];


    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $imageMaps = [
            'hoodies' => [
                "https://m.media-amazon.com/images/I/71SStjOCNcL._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/11++B3A2NEL._SS200_.png",
                "https://m.media-amazon.com/images/I/61+8jjZWGdL._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/81naTg9Yn3L._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/11++B3A2NEL._SS200_.png",
                "https://m.media-amazon.com/images/I/81gFq9CKvPL._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/71xORUaQ9tL._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/11++B3A2NEL._SS200_.png",
                "https://m.media-amazon.com/images/I/81vSH1-yW6L._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/81naTg9Yn3L._AC_UL320_.jpg",
                "https://m.media-amazon.com/images/I/81vSH1-yW6L._AC_UL320_.jpg"
            ],
            'bags' => [
                'https://m.media-amazon.com/images/I/61IKi2fKoSL._AC_UY218_.jpg',
                'ttps://m.media-amazon.com/images/I/81HMnRpL9OL._AC_UY218_.jpg',
                'https://m.media-amazon.com/images/I/81JRCVU-z0L._AC_UY218_.jpg',
                'https://m.media-amazon.com/images/I/91PF4HmzZML._AC_UY218_.jpg',
                'https://m.media-amazon.com/images/I/51nbs+rrPQL._AC_UY218_.jpg',
                'https://m.media-amazon.com/images/I/91hOUJjsssL._AC_UY218_.jpg',
                'https://m.media-amazon.com/images/I/61zMfXiITGL._AC_UY218_.jpg',
                'https://m.media-amazon.com/images/I/61YKKRDbm+L._AC_UY218_.jpg'
            ],
            'shoes' => [
                'https://m.media-amazon.com/images/I/61kcnEgkYnL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71o8YHIv1eL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61WjMCCZusL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71HhEuEOc8L._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71GxSR0LBlL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71zYzOhdw8L._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71knpEWfnLL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61lnX1lRCWL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61LTcdqB1IL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61EURgh3v2L._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71NxSqaW9ZL._AC_UL320_.jpg'
            ],
            'accessories' => [
                'https://m.media-amazon.com/images/I/711b3iSF-1L._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71t6XC9OtoL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61n1sYhl9mL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/712lUnThXpL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/81wWdaTkjrL._AC_UL320_.jpg'
            ],
            'jackets' => [
                'https://m.media-amazon.com/images/I/61UAwxBzgpL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/81SzjyKLJmL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71Jx5e9GbKL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71rYolw9yoL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71gvJnWVtyL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71kq8Dsw2iL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61tq4kV0iBL._AC_UL320_.jpg'
            ],
            'shorts' => [
                'https://m.media-amazon.com/images/I/810ZOnAf7SL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/81Grnfh3otL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71rjcO4nkSL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61IKSIknNKL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71L2uiu8vpL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/61ReTiqk8eL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/71L9Qe0HQNL._AC_UL320_.jpg',
                'https://m.media-amazon.com/images/I/716A1Qb6zXL._AC_UL320_.jpg'
            ]
        ];

        User::factory(1)->create();
        Category::factory(6)->create()->each(function ($category) use ($imageMaps) {
            Product::factory(16)->state([
                'category_id' => $category->id
            ])->create()->each(function ($product) use ($category, $imageMaps) {
                $currentIndex = 0;
                $categoryName = $category->title;
                $imageUrls = $imageMaps[$categoryName] ?? $imageMaps['hoodie'];
                // dd($imageUrls);
                ProductImage::factory(3)->state([
                    'product_id' => $product->id,
                    'url' => $imageUrls[$currentIndex]
                ])->create();
                $currentIndex + 1;
                // Ambil array URL gambar yang sesuai dengan kategori ini
                ProductSize::factory(3)->state(['product_id' => $product->id])->create();
                ProductColor::factory(2)->state(['product_id' => $product->id])->create();
            });
        });


        // Order::factory(1)->create()->each(function ($order) {
        //     $products = Product::inRandomOrder()->take(rand(1, 4))->get();
        //     $itemCount = 0;
        //     $total = 0;
        //     foreach ($products as $p) {
        //         $qty = rand(1, 3);
        //         $price = (float) $p->price;
        //         $itemCount += $qty;
        //         $total += $qty * $price;

        //         OrderedProduct::factory()->state([
        //             'order_id' => $order->id,
        //             'product_id' => $p->id,
        //             'quantity' => $qty,
        //             'price' => $price,
        //             'color' => $p->colors()->exists() ? $p->colors()->inRandomOrder()->first()->title : null,
        //             'size' => $p->sizes()->exists() ? $p->sizes()->inRandomOrder()->first()->size : null,
        //         ])->create();
        //     }

        //     $order->item_count = $itemCount;
        //     $order->total_price = $total;
        //     $order->save();

        //     // initial status pending
        //     OrderStatus::factory()->state(['order_id' => $order->id, 'status' => 'pending'])->create();

        //     // optionally mark some as paid
        //     if (rand(0, 1)) {
        //         OrderStatus::factory()->state(['order_id' => $order->id, 'status' => 'paid'])->create();
        //     }
        // });

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call([
        //     SongSeeder::class
        // ]);
    }
}
