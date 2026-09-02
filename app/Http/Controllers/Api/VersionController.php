<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class VersionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'current_version' => 'v2',

            'versions' => [
                [
                    'version' => 'v1',
                    'status' => 'deprecated',
                    'released_at' => '2026-08-01',
                    'sunset_at' => '2027-01-01',
                    'migration_to' => 'v2',

                    'endpoint' => url('/api/v1/products'),

                    'migration_endpoint' => url(
                        '/api/v2/products'
                    ),

                    'message' =>
                        'V1 is deprecated. Please migrate to V2 before the sunset date.',

                    'features' => [
                        'Product CRUD',
                        'Pagination',
                        'Basic product fields',
                    ],

                    'removed_features' => [
                        'Search',
                        'Category filtering',
                        'Active status filtering',
                    ],
                ],

                [
                    'version' => 'v2',
                    'status' => 'current',
                    'released_at' => '2026-09-01',
                    'sunset_at' => null,
                    'migration_to' => null,

                    'endpoint' => url(
                        '/api/v2/products'
                    ),

                    'migration_endpoint' => null,

                    'message' =>
                        'V2 is the current and recommended API version.',

                    'features' => [
                        'Product CRUD',
                        'Pagination',
                        'Search',
                        'Category filtering',
                        'Active status filtering',
                        'Extended product response',
                        'Created and updated timestamps',
                    ],

                    'removed_features' => [],
                ],
            ],
        ]);
    }
}