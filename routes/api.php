<?php

use App\Http\Controllers\Api\V1\ProductController as ProductV1;
use App\Http\Controllers\Api\V2\ProductController as ProductV2;
use App\Http\Controllers\Api\VersionController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Health Check
|--------------------------------------------------------------------------
*/

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

        Cache::put('health_check', true, 1);

        $checks['cache'] = Cache::has('health_check');

    } catch (\Exception $e) {

        $checks['cache'] = false;

    }

    $healthy = collect($checks)->every(
        fn ($value) => $value === true
    );

    return response()->json([
        'status' => $healthy ? 'ok' : 'error',
        'driver' => $driver,
        'checks' => $checks,
    ], $healthy ? 200 : 503);
});


/*
|--------------------------------------------------------------------------
| API Version Information
|--------------------------------------------------------------------------
*/

Route::get('versions', [VersionController::class, 'index'])
    ->name('api.versions');


/*
|--------------------------------------------------------------------------
| API V1 - Deprecated
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->middleware('api.version.deprecation')
    ->group(function () {

        Route::apiResource(
            'products',
            ProductV1::class
        )
        ->only([
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ])
        ->names([
            'index' => 'api.v1.products.index',
            'store' => 'api.v1.products.store',
            'show' => 'api.v1.products.show',
            'update' => 'api.v1.products.update',
            'destroy' => 'api.v1.products.destroy',
        ]);

    });


/*
|--------------------------------------------------------------------------
| API V2 - Current
|--------------------------------------------------------------------------
*/

Route::prefix('v2')
    ->group(function () {

        Route::apiResource(
            'products',
            ProductV2::class
        )
        ->only([
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ])
        ->names([
            'index' => 'api.v2.products.index',
            'store' => 'api.v2.products.store',
            'show' => 'api.v2.products.show',
            'update' => 'api.v2.products.update',
            'destroy' => 'api.v2.products.destroy',
        ]);

    });