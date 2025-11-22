<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MeditationController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\ProductController;
use App\Http\Controllers\Ecommerce\CategoryController;
use App\Http\Controllers\Ecommerce\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/meditation', [MeditationController::class, 'getDailyQuotes']);
Route::get('/getmood', [MeditationController::class, 'getAdviceByMood']);
Route::get('/songs', [SongController::class, 'getAllSongs']);


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('api.auth.register');
    Route::post('/login', 'login')->name('api.auth.login');
    // Route::post('/reset/otp', 'resetOtp')->name('api.auth.reset.otp');
    // Route::post('/reset/password', 'resetPassword')->name('api.auth.reset.password');

    Route::middleware('auth:sanctum')->group(function () {
        // Route::post('/otp', 'otp')->name('api.auth.otp');
        // Route::post('/verify', 'verify')->name('api.auth.verify');
        Route::post('/logout', 'logout')->name('api.auth.logout');
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/get-categories', 'getCategories')->name('api.get-categories');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::get('/get-top-selling', 'getTopSelling')->name('api.get-top-selling');
            Route::get('/get-new-in', 'getNewIn')->name('api.get-new-in');
            Route::post('/toggle-favorite', 'toggleFavorite')->name('api.toggle-favorite');
            Route::get('/get-favorite-products', 'getFavoriteProducts')->name('api.get-favorite-products');
            Route::get('/get-products-byid-category', 'getProductsByIdCategory')->name('api.get-products-byid-category');
            Route::get('/get-products-by-title', 'getProductsByTitle')->name('api.get-products-by-title');
        });

        Route::controller(CartController::class)->group(function () {
            Route::post('/add-to-cart', 'addToCart')->name('api.add-to-cart');
            Route::get('/get-product-carts', 'getProductCarts')->name('api.get-product-carts');
            Route::delete('/remove-cart-product', 'removeCartProduct')->name('api.remove-cart-product');
        });

        Route::controller(OrderController::class)->group(function () {
            Route::post('/order-registration', 'orderRegistration')->name('api.order-registration');
            Route::get('/get-orders', 'getOrders')->name('api.get-orders');
        });
    });
});
