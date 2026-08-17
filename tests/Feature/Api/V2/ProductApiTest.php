<?php

namespace Tests\Feature\Api\V2;

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

        $response = $this->getJson('/api/v2/products?page=1&per_page=2');

        $response->assertOk()
            ->assertJsonStructure([
                'version',
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'sku', 'stock', 'category', 'is_active', 'created_at', 'updated_at'],
                ],
                'pagination' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    #[Test]
    public function it_shows_a_product_with_v2_fields(): void
    {
        $product = Product::factory()->create([
            'category' => 'Electronics',
            'is_active' => false,
        ]);

        $response = $this->getJson("/api/v2/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('version', 'v2')
            ->assertJsonPath('data.category', 'Electronics')
            ->assertJsonPath('data.is_active', false);
    }

    #[Test]
    public function it_creates_a_product_with_v2_fields(): void
    {
        $response = $this->postJson('/api/v2/products', [
            'name' => 'Gaming Laptop',
            'description' => 'High-end laptop',
            'price' => 1999.99,
            'sku' => 'PRD-GAMING-01',
            'stock' => 15,
            'category' => 'Electronics',
            'is_active' => true,
        ]);

        $response->assertCreated()
            ->assertJsonPath('version', 'v2')
            ->assertJsonPath('data.category', 'Electronics')
            ->assertJsonPath('data.is_active', true);
    }

    #[Test]
    public function it_validates_store_request(): void
    {
        $response = $this->postJson('/api/v2/products', ['name' => 'Test']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['price', 'sku', 'stock']);
    }

    #[Test]
    public function it_supports_search_and_filter(): void
    {
        Product::factory()->create(['name' => 'Gaming Laptop', 'is_active' => true, 'category' => 'Electronics']);
        Product::factory()->create(['name' => 'Wireless Mouse', 'is_active' => false, 'category' => 'Accessories']);

        $response = $this->getJson('/api/v2/products?search=laptop');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    #[Test]
    public function it_updates_a_product(): void
    {
        $product = Product::factory()->create(['name' => 'Original']);

        $response = $this->putJson("/api/v2/products/{$product->id}", [
            'name' => 'Updated',
            'category' => 'New Category',
            'is_active' => false,
        ]);

        $response->assertOk()
            ->assertJsonPath('version', 'v2')
            ->assertJsonPath('data.name', 'Updated')
            ->assertJsonPath('data.category', 'New Category')
            ->assertJsonPath('data.is_active', false);
    }

    #[Test]
    public function it_deletes_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v2/products/{$product->id}");

        $response->assertNoContent();
        $this->assertModelMissing($product);
    }
}
