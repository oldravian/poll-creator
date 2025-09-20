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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
            \Illuminate\Support\Facades\Request::setTrustedProxies(
                ['*'], 
                \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR | 
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST | 
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT | 
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
            );
        }
    }
}