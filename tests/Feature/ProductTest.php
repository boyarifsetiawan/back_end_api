<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\TestResponse;
use App\Repositories\ProductRepository;
use App\Http\Controllers\Ecommerce\ProductController;



it('requires authentication to access top selling products', function () {
    $response = $this->getJson('/api/get-top-selling');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});

it('returns top selling products for authenticated user', function () {
    // Hit endpoint
    $response = $this->actingAs($this->user)->getJson('/api/get-top-selling');

    // Assertions
    $response->assertStatus(200);
    $response->assertJsonStructure($this->productListStructure());
});

it('requires authentication to access get new products', function () {
    $response = $this->getJson('/api/get-new-in');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});

it('returns new in products for authenticated user', function () {

    $response = $this->actingAs($this->user)->getJson('/api/get-top-selling');

    // Assertions
    $response->assertStatus(200);
    $response->assertJsonStructure($this->productListStructure());
});

it('can add a product to favorites', function () {
    $response = $this->actingAs($this->user)->postJson('/api/toggle-favorite', [
        'product_id' => $this->products->id,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Produk berhasil ditambahkan ke favorit.',
            'status' => true,
        ]);

    // Pastikan di database ada relasi favorite
    $this->assertDatabaseHas('favorite_products', [
        'user_id' => $this->user->id,
        'product_id' => $this->products->id,
    ]);
});


it('can remove a products from favorite', function () {
    $this->user->favorites()->attach($this->products->id);

    $repsonse = $this->actingAs($this->user)->postJson('/api/toggle-favorite', [
        'product_id' => $this->products->id
    ]);

    $repsonse->assertStatus(200)->assertJson([
        'message' => 'Produk berhasil dihapus dari favorit.',
        'status' => false,
    ]);

    $this->assertDatabaseMissing('favorite_products', [
        'user_id' => $this->user->id,
        'product_id' => $this->products->id,
    ]);
});

it('requires authentication to access get favorite products', function () {
    $response = $this->getJson('/api/get-favorite-products');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});

it('returns favorites products for authenticated user', function () {

    $response = $this->actingAs($this->user)->getJson('/api/get-favorite-products');

    // Assertions
    $response->assertStatus(200);
    $response->assertJsonStructure($this->productListStructure());
});


it('requires authentication to access products by id category', function () {
    $response = $this->getJson('/api/get-favorite-products');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});


it('returs products by id category for authenticated user', function () {
    // 1. Setup: Membuat Kategori target dan kategori lain
    $targetCategory = Category::factory()->create();
    $otherCategory = Category::factory()->create();

    // 2. Setup: Membuat produk untuk kategori target (3 produk)
    $productsTarget = Product::factory(3)->create(['category_id' => $targetCategory->id]);

    // 3. Setup: Membuat produk untuk kategori lain (1 produk)
    Product::factory()->create(['category_id' => $otherCategory->id]);
    // 4. Aksi: Melakukan GET request ke endpoint dengan query_params
    $response = $this->actingAs($this->user)->getJson('/api/get-products-byid-category' . '?query_params=' . $targetCategory->id);

    // 5. Assertion
    $response->assertStatus(200);

    // Memastikan struktur JSON sesuai dengan helper global
    $response->assertJsonStructure($this->productListStructure());

    // Memastikan jumlah item yang dikembalikan adalah 3
    $response->assertJsonCount(3, 'results');

    // Memastikan hanya produk dari kategori target yang dikembalikan
    $response->assertJsonStructure($this->productListStructure());
});


test('returns empty array if query params not given or ID not found', function () {
    // 1. Aksi 1: Request tanpa query_params
    $responseNoParam = $this->actingAs($this->user)->getJson('/api/get-products-byid-category');

    // 2. Aksi 2: Request dengan ID yang tidak mungkin ada (misal: 9999)
    $responseNotFound = $this->actingAs($this->user)->getJson('/api/get-products-byid-category' . '?query_params=9999');

    $responseNoParam->assertStatus(400);
    $responseNoParam->assertJsonCount(0, 'results');

    // Assertion 2: Respons dengan ID tidak ditemukan
    $responseNotFound->assertStatus(200);
    $responseNotFound->assertJsonCount(0, 'results');
});

test('dapat mengambil produk berdasarkan sebagian judul yang cocok', function () {
    // 1. Setup Data: Buat produk yang akan dicari
    $targetProduct = Product::factory()->create(['title' => 'Tas Ransel Gunung Terbaru']);

    // Buat produk lain agar hasil pencarian spesifik
    Product::factory()->create(['title' => 'Sepatu Lari Sporty']);

    $searchTitle = 'Ransel Gunung';

    // 2. Aksi: Melakukan GET request
    $response = $this->actingAs($this->user)->getJson('/get-products-by-title' . '?query_params=' . $searchTitle);

    // 3. Assertion
    $response->assertStatus(200);

    // Memastikan struktur JSON sesuai dengan helper global
    $response->assertJsonStructure($this->productListStructure());

    // Memastikan hanya produk target yang ada (atau setidaknya produk target muncul)
    $response->assertJsonFragment([
        'id' => $targetProduct->id,
        'title' => 'Tas Ransel Gunung Terbaru',
    ]);

    // Memastikan jumlah item yang dikembalikan adalah 1
    $response->assertJsonCount(1, 'results');
});

// --- Skenario 2: TIDAK DITEMUKAN (404 Not Found) ---
test('mengembalikan 404 jika judul pencarian tidak menemukan produk', function () {
    // Setup Data: Tidak ada produk yang cocok dengan string pencarian
    Product::factory(5)->create(); // Buat data dummy
    $nonExistentTitle = 'Judul Yang Pasti Tidak Ada 999';

    // 2. Aksi
    $response = $this->actingAs($this->user)->getJson('/get-products-by-title' . '?query_params=' . $nonExistentTitle);

    // 3. Assertion
    $response->assertStatus(404);

    // Memastikan pesan yang dikembalikan benar
    $response->assertJson([
        'message' => 'Product Not Found',
        'results' => []
    ]);
});


// --- Skenario 3: VALIDASI ERROR (400 Bad Request) ---
test('mengembalikan 400 jika parameter query_params (judul) kosong', function () {
    // Aksi 1: Request tanpa query_params sama sekali
    $responseEmpty = $this->actingAs($this->user)->getJson('/get-products-by-title');

    // Aksi 2: Request dengan query_params yang nilainya kosong
    $responseBlank = $this->actingAs($this->user)->getJson('/get-products-by-title' . '?query_params=');

    // Assertion 1: Respons tanpa parameter
    $responseEmpty->assertStatus(400);
    $responseEmpty->assertJson([
        'message' => 'Validation Error: Product title search term (query_params) is required.',
        'results' => []
    ]);

    // Assertion 2: Respons dengan parameter kosong
    $responseBlank->assertStatus(400);
    $responseBlank->assertJson([
        'message' => 'Validation Error: Product title search term (query_params) is required.',
        'results' => []
    ]);
});
