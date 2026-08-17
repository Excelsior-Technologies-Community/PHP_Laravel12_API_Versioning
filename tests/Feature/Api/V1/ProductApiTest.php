<?php

namespace Tests\Feature\Api\V1;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_paginated_products_list(): void
    {
        Product::factory(5)->create();

        $response = $this->getJson('/api/v1/products?page=1&per_page=2');

        $response->assertOk()
            ->assertJsonStructure([
                'version',
                'data' => [
                    '*' => ['id', 'name', 'price', 'sku', 'stock'],
                ],
                'pagination' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    #[Test]
    public function it_shows_a_single_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('version', 'v1')
            ->assertJsonPath('data.id', $product->id);
    }

    #[Test]
    public function it_creates_a_product(): void
    {
        $response = $this->postJson('/api/v1/products', [
            'name' => 'Test Product',
            'description' => 'A test product',
            'price' => 99.99,
            'sku' => 'PRD-TEST-01',
            'stock' => 10,
        ]);

        $response->assertCreated()
            ->assertJsonPath('version', 'v1')
            ->assertJsonPath('data.name', 'Test Product');

        $this->assertDatabaseHas('products', ['sku' => 'PRD-TEST-01']);
    }

    #[Test]
    public function it_validates_store_request(): void
    {
        $response = $this->postJson('/api/v1/products', ['name' => 'Test']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['price', 'sku', 'stock']);
    }

    #[Test]
    public function it_updates_a_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Original Name',
            'price' => 50.00,
        ]);

        $response = $this->putJson("/api/v1/products/{$product->id}", [
            'name' => 'Updated Name',
            'price' => 75.00,
        ]);

        $response->assertOk()
            ->assertJsonPath('version', 'v1')
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.price', 75);
    }

    #[Test]
    public function it_deletes_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertNoContent();
        $this->assertModelMissing($product);
    }
}
