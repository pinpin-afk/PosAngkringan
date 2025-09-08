<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure asset URLs are not forced to an http domain
        config(['app.asset_url' => null]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force https when served behind reverse proxy (e.g., ngrok) or production
        try {
            $appUrl = (string) config('app.url');
            $forwardedProto = request()->header('x-forwarded-proto');

            if (
                app()->environment('production') ||
                str_contains($appUrl, 'ngrok') ||
                $forwardedProto === 'https'
            ) {
                URL::forceScheme('https');
            }
        } catch (\Throwable $e) {
            // no-op if request() not available during CLI
        }
    }
}
