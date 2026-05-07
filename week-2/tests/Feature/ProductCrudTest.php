<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_with_valid_payload(): void
    {
        $response = $this->post(route('products.store'), [
            'name' => 'Trail Shoes',
            'sku' => 'trl-001',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Comfortable running shoes',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'name' => 'Trail Shoes',
            'sku' => 'TRL-001',
            'status' => Product::STATUS_ACTIVE,
        ]);
    }

    public function test_create_fails_for_invalid_status(): void
    {
        $response = $this->from(route('products.create'))->post(route('products.store'), [
            'name' => 'Trail Shoes',
            'sku' => 'trl-002',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => 'invalid_status',
            'description' => 'Comfortable running shoes',
        ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors(['status']);
    }

    public function test_create_fails_for_duplicate_sku(): void
    {
        Product::query()->create([
            'name' => 'Existing Product',
            'sku' => 'DUP-001',
            'price' => 45.00,
            'stock_qty' => 5,
            'status' => Product::STATUS_DRAFT,
            'description' => null,
        ]);

        $response = $this->from(route('products.create'))->post(route('products.store'), [
            'name' => 'New Product',
            'sku' => 'dup-001',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Duplicate SKU check',
        ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors(['sku']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::query()->create([
            'name' => 'Old Name',
            'sku' => 'OLD-001',
            'price' => 10.00,
            'stock_qty' => 2,
            'status' => Product::STATUS_DRAFT,
            'description' => null,
        ]);

        $response = $this->put(route('products.update', $product), [
            'name' => ' Updated Product ',
            'sku' => 'upd-001',
            'price' => 15.50,
            'stock_qty' => 10,
            'status' => Product::STATUS_ACTIVE,
            'description' => ' Updated description ',
        ]);

        $response->assertRedirect(route('products.show', $product));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'sku' => 'UPD-001',
            'status' => Product::STATUS_ACTIVE,
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::query()->create([
            'name' => 'Delete Me',
            'sku' => 'DEL-001',
            'price' => 10.00,
            'stock_qty' => 2,
            'status' => Product::STATUS_DRAFT,
            'description' => null,
        ]);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
