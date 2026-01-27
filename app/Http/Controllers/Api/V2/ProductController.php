<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'version' => 'v2',
            'products' => [
                ['id' => 1, 'name' => 'Laptop V2'],
                ['id' => 2, 'name' => 'Mobile V2'],
            ]
        ]);
    }
}
