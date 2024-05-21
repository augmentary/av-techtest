<?php

namespace App\Providers;

use App\Auth\ApiTokenUserFetcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::viaRequest('api-token', $this->app->make(ApiTokenUserFetcher::class));

    }
}
