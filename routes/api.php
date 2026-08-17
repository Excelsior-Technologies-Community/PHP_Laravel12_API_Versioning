<?php

use App\Http\Controllers\Api\V1\ProductController as ProductV1;
use App\Http\Controllers\Api\V2\ProductController as ProductV2;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('health', function () {
    $checks = [
        'database' => false,
        'cache' => false,
    ];

    $driver = config('database.default');

    try {
        DB::connection()->getPdo();
        $checks['database'] = true;
    } catch (\Exception $e) {
        $checks['database'] = false;
    }

    try {
        \Illuminate\Support\Facades\Cache::put('health_check', true, 1);
        $checks['cache'] = Cache::has('health_check');
    } catch (\Exception $e) {
        $checks['cache'] = false;
    }

    return response()->json([
        'status' => collect($checks)->every(fn ($v) => $v) ? 'ok' : 'error',
        'driver' => $driver,
        'checks' => $checks,
    ], collect($checks)->every(fn ($v) => $v) ? 200 : 503);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductV1::class)
        ->only(['index', 'store', 'show', 'update', 'destroy'])
        ->names([
            'index' => 'api.v1.products.index',
            'store' => 'api.v1.products.store',
            'show' => 'api.v1.products.show',
            'update' => 'api.v1.products.update',
            'destroy' => 'api.v1.products.destroy',
        ]);
});

Route::prefix('v2')->group(function () {
    Route::apiResource('products', ProductV2::class)
        ->only(['index', 'store', 'show', 'update', 'destroy'])
        ->names([
            'index' => 'api.v2.products.index',
            'store' => 'api.v2.products.store',
            'show' => 'api.v2.products.show',
            'update' => 'api.v2.products.update',
            'destroy' => 'api.v2.products.destroy',
        ]);
});
