<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interface\AuthenticationRepositoryInterface::class,
            \App\Repositories\AuthRepositoryImpl::class
        );
        $this->app->bind(
            \App\Interface\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepositoryImpl::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
