<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'version' => 'v1',
            'products' => [
                ['id' => 1, 'name' => 'Laptop V1'],
            ]
        ]);
    }
}
