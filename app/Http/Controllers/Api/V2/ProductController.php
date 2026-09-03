<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\StoreProductRequest;
use App\Http\Requests\V2\UpdateProductRequest;
use App\Http\Resources\V2\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Product Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'sku',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'category',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Active / Inactive
        |--------------------------------------------------------------------------
        */

        if (
            $request->has('is_active')
            && $request->is_active !== ''
        ) {

            $isActive = filter_var(
                $request->is_active,
                FILTER_VALIDATE_BOOLEAN
            );

            $query->where(
                'is_active',
                $isActive
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Featured
        |--------------------------------------------------------------------------
        */

        if (
            $request->has('is_featured')
            && $request->is_featured !== ''
        ) {

            $isFeatured = filter_var(
                $request->is_featured,
                FILTER_VALIDATE_BOOLEAN
            );

            $query->where(
                'is_featured',
                $isFeatured
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Status
        |--------------------------------------------------------------------------
        |
        | in_stock      = stock > 10
        | low_stock     = stock 1 - 10
        | out_of_stock  = stock = 0
        |
        */

        if ($request->filled('stock_status')) {

            switch ($request->stock_status) {

                case 'in_stock':

                    $query->where(
                        'stock',
                        '>',
                        10
                    );

                    break;

                case 'low_stock':

                    $query->whereBetween(
                        'stock',
                        [1, 10]
                    );

                    break;

                case 'out_of_stock':

                    $query->where(
                        'stock',
                        0
                    );

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->get(
            'sort',
            'id'
        );

        $allowedSorts = [
            'id',
            'name',
            'price',
            'stock',
            'created_at',
        ];

        if (
            !in_array(
                $sort,
                $allowedSorts,
                true
            )
        ) {

            $sort = 'id';
        }

        $sortOrder = strtolower(
            $request->get(
                'sort_order',
                'asc'
            )
        );

        if (
            !in_array(
                $sortOrder,
                ['asc', 'desc'],
                true
            )
        ) {

            $sortOrder = 'asc';
        }

        $query->orderBy(
            $sort,
            $sortOrder
        );

        /*
        |--------------------------------------------------------------------------
        | Always 5 Products Per Page
        |--------------------------------------------------------------------------
        */

        $products = $query->paginate(5);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'version' => 'v2',

            'status' => 'current',

            'data' => ProductResource::collection(
                $products
            ),

            'pagination' => [

                'current_page' =>
                    $products->currentPage(),

                'last_page' =>
                    $products->lastPage(),

                'per_page' =>
                    $products->perPage(),

                'total' =>
                    $products->total(),

            ],

            'filters' => [

                'search' =>
                    $request->search,

                'is_active' =>
                    $request->is_active,

                'category' =>
                    $request->category,

                'is_featured' =>
                    $request->is_featured,

                'min_price' =>
                    $request->min_price,

                'max_price' =>
                    $request->max_price,

                'stock_status' =>
                    $request->stock_status,

            ],

            'sorting' => [

                'sort' =>
                    $sort,

                'sort_order' =>
                    $sortOrder,

            ],

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): JsonResponse
    {
        return response()->json([

            'version' => 'v2',

            'statistics' => [

                'total_products' =>
                    Product::count(),

                'active_products' =>
                    Product::where(
                        'is_active',
                        true
                    )->count(),

                'inactive_products' =>
                    Product::where(
                        'is_active',
                        false
                    )->count(),

                'featured_products' =>
                    Product::where(
                        'is_featured',
                        true
                    )->count(),

                'in_stock_products' =>
                    Product::where(
                        'stock',
                        '>',
                        0
                    )->count(),

                'out_of_stock_products' =>
                    Product::where(
                        'stock',
                        0
                    )->count(),

                'average_price' =>
                    round(
                        (float) Product::avg('price'),
                        2
                    ),

                'highest_price' =>
                    (float) (
                        Product::max('price') ?? 0
                    ),

                'lowest_price' =>
                    (float) (
                        Product::min('price') ?? 0
                    ),

                'total_stock' =>
                    Product::sum('stock'),

            ],

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreProductRequest $request
    ): JsonResponse {

        $product = Product::create(
            $request->validated()
        );

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Product created successfully.',

            'data' =>
                new ProductResource($product),

        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Product $product
    ): JsonResponse {

        return response()->json([

            'version' => 'v2',

            'data' =>
                new ProductResource($product),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): JsonResponse {

        $product->update(
            $request->validated()
        );

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Product updated successfully.',

            'data' =>
                new ProductResource(
                    $product->fresh()
                ),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Product $product
    ): JsonResponse {

        $product->delete();

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Product moved to trash successfully.',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */

    public function bulkDelete(
        Request $request
    ): JsonResponse {

        $request->validate([

            'ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ids.*' => [
                'integer',
                'exists:products,id',
            ],

        ]);

        $deleted = Product::whereIn(
            'id',
            $request->ids
        )->delete();

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Products moved to trash successfully.',

            'deleted_count' =>
                $deleted,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore(
        int $id
    ): JsonResponse {

        $product = Product::withTrashed()
            ->findOrFail($id);

        if (!$product->trashed()) {

            return response()->json([

                'message' =>
                    'Product is not in trash.',

            ], 422);
        }

        $product->restore();

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Product restored successfully.',

            'data' =>
                new ProductResource($product),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Force Delete
    |--------------------------------------------------------------------------
    */

    public function forceDelete(
        int $id
    ): JsonResponse {

        $product = Product::withTrashed()
            ->findOrFail($id);

        $product->forceDelete();

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Product permanently deleted.',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Trash
    |--------------------------------------------------------------------------
    */

    public function trash(): JsonResponse
    {
        $products = Product::onlyTrashed()
            ->oldest('deleted_at')
            ->paginate(5);

        return response()->json([

            'version' => 'v2',

            'data' =>
                ProductResource::collection(
                    $products
                ),

            'pagination' => [

                'current_page' =>
                    $products->currentPage(),

                'last_page' =>
                    $products->lastPage(),

                'per_page' =>
                    $products->perPage(),

                'total' =>
                    $products->total(),

            ],

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Active Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Product $product
    ): JsonResponse {

        $product->update([

            'is_active' =>
                !$product->is_active,

        ]);

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Product status updated successfully.',

            'is_active' =>
                $product->is_active,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Featured
    |--------------------------------------------------------------------------
    */

    public function toggleFeatured(
        Product $product
    ): JsonResponse {

        $product->update([

            'is_featured' =>
                !$product->is_featured,

        ]);

        return response()->json([

            'version' => 'v2',

            'message' =>
                'Featured status updated successfully.',

            'is_featured' =>
                $product->is_featured,

        ]);
    }
}