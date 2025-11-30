<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MeditationController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Http\Controllers\Ecommerce\ProductController;
use App\Http\Controllers\Ecommerce\CategoryController;
use App\Http\Controllers\Api\FileManager\FileController;
use App\Http\Controllers\Api\Portofolio\SkillController;
use App\Http\Controllers\Api\Portofolio\ProfileController;
use App\Http\Controllers\Api\Portofolio\ProjectController;

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

// PORTO SECTION
Route::middleware(['auth:sanctum', 'api'])->group(function () {
    Route::get('/get-skills', [SkillController::class, 'getSkills']);
    Route::post('/create-skill', [SkillController::class, 'createSkill']);
    Route::post('/update-skill', [SkillController::class, 'updateSkill']);
    Route::delete('/delete-skill', [SkillController::class, 'deleteSkill']);
    Route::get('/get-skill-byid', [SkillController::class, 'getSkillById']);

    Route::get('/get-projects', [ProjectController::class, 'getProjects']);
    Route::post('/create-project', [ProjectController::class, 'createProject']);
    Route::post('/update-project', [ProjectController::class, 'updateProject']);
    Route::delete('/delete-project', [ProjectController::class, 'deleteProject']);
    Route::get('/get-project-byid', [ProjectController::class, 'getProjectById']);

    Route::get('/get-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/update-profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/delete-profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/get-projects-skills', [ProjectController::class, 'getProjectsSkills']);
Route::get('/get-project-detail', [ProjectController::class, 'getProjectDetail']);


Route::prefix('multipurpose')->controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::post('/users', 'store');
    Route::patch('/users/{user}/change-role', 'changeRole');
    Route::put('/users/{user}', 'update');
    Route::delete('/users/{user}', 'destroy');
    Route::delete('/users', 'bulkDelete');

    Route::get('/appointments', 'index');
    Route::post('/appointments/create', 'store');
    Route::get('/appointments/{appointment}/edit', 'edit');
    Route::put('/appointments/{appointment}/update', 'update');
    Route::delete('/appointments/{appointment}', 'destroy');
    Route::get('/appointment-status', 'getStatusWithCount');
    Route::get('/clients', 'index');
});



// FIle manageer
Route::controller(FileController::class)
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/my-files/{folder?}', 'myFiles')
            ->where('folder', '(.*)')
            ->name('myFiles');
        Route::post('/folder/create', 'createFolder')->name('folder.create');
        Route::post('/file', 'store')->name('file.store');
        Route::delete('/file', 'destroy')->name('file.delete');

        Route::get('/file/download', 'download')->name('file.download');
        Route::get('/trash', 'trash')->name('trash');
        Route::post('/file/restore', 'restore')->name('file.restore');
        Route::delete('/file/delete-forever', 'deleteForever')->name('file.deleteForever');
        Route::post('/file/add-to-favourites', 'addToFavourites')->name('file.addToFavourites');
        Route::post('/file/share', 'share')->name('file.share');
        Route::get('/shared-with-me', 'sharedWithMe')->name('file.sharedWithMe');
        Route::get('/shared-by-me', 'sharedByMe')->name('file.sharedByMe');
        Route::get('/file/download-shared-with-me', 'downloadSharedWithMe')->name('file.downloadSharedWithMe');
        Route::get('/file/download-shared-by-me', 'downloadSharedByMe')->name('file.downloadSharedByMe');
    });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
