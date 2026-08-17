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
        if (config('database.default') === 'mysql') {
            try {
                \Illuminate\Support\Facades\DB::connection()->getPdo();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Config::set('database.default', 'sqlite');
                \Illuminate\Support\Facades\Config::set('database.connections.sqlite.database', database_path('database.sqlite'));
            }
        }
    }
}
