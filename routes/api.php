<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController as ProductV1;
use App\Http\Controllers\Api\V2\ProductController as ProductV2;

Route::prefix('v1')->group(function () {
  Route::get('products', [ProductV1::class, 'index']);
});

Route::prefix('v2')->group(function () {
  Route::get('products', [ProductV2::class, 'index']);
});
