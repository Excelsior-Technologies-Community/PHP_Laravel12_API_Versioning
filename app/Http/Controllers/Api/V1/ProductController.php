<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::paginate(
            $request->get('per_page', 15)
        );

        return response()->json([
            'version' => 'v1',
            'status' => 'deprecated',
            'message' => 'V1 is deprecated. Please migrate to V2.',
            'data' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'migration' => [
                'latest_version' => 'v2',
                'endpoint' => url('/api/v2/products'),
                'sunset_at' => '2027-01-01',
            ],
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'version' => 'v1',
            'status' => 'deprecated',
            'message' => 'Product created successfully. Note: V1 is deprecated.',
            'data' => new ProductResource($product),
            'migration' => [
                'latest_version' => 'v2',
                'endpoint' => url('/api/v2/products'),
            ],
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'version' => 'v1',
            'status' => 'deprecated',
            'data' => new ProductResource($product),
            'migration' => [
                'latest_version' => 'v2',
                'endpoint' => url('/api/v2/products/' . $product->id),
            ],
        ]);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): JsonResponse {
        $product->update($request->validated());

        return response()->json([
            'version' => 'v1',
            'status' => 'deprecated',
            'message' => 'Product updated successfully. Note: V1 is deprecated.',
            'data' => new ProductResource($product),
            'migration' => [
                'latest_version' => 'v2',
                'endpoint' => url('/api/v2/products/' . $product->id),
            ],
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([], 204);
    }
}