<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_with_valid_payload(): void
    {
        $response = $this->post(route('products.store'), [
            'name' => 'Trail Shoes',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Comfortable running shoes',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'name' => 'Trail Shoes',
            'status' => Product::STATUS_ACTIVE,
        ]);
    }

    public function test_create_fails_for_invalid_status(): void
    {
        $response = $this->from(route('products.create'))->post(route('products.store'), [
            'name' => 'Trail Shoes',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => 'invalid_status',
            'description' => 'Comfortable running shoes',
        ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors(['status']);
    }

    public function test_can_create_product_with_image_upload(): void
    {
        Storage::fake('public');

        $response = $this->post(route('products.store'), [
            'name' => 'Trail Shoes',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Comfortable running shoes',
            'image' => UploadedFile::fake()->image('trail-shoes.jpg'),
        ]);

        $response->assertRedirect();

        $product = Product::query()->firstOrFail();

        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_create_fails_for_invalid_image_file(): void
    {
        Storage::fake('public');

        $response = $this->from(route('products.create'))->post(route('products.store'), [
            'name' => 'Trail Shoes',
            'price' => 99.90,
            'stock_qty' => 15,
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Comfortable running shoes',
            'image' => UploadedFile::fake()->create('manual.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors(['image']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::query()->create([
            'name' => 'Old Name',
            'price' => 10.00,
            'stock_qty' => 2,
            'status' => Product::STATUS_DRAFT,
            'description' => null,
        ]);

        $response = $this->put(route('products.update', $product), [
            'name' => ' Updated Product ',
            'price' => 15.50,
            'stock_qty' => 10,
            'status' => Product::STATUS_ACTIVE,
            'description' => ' Updated description ',
        ]);

        $response->assertRedirect(route('products.show', $product));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'status' => Product::STATUS_ACTIVE,
        ]);
    }

    public function test_updating_product_image_replaces_previous_file(): void
    {
        Storage::fake('public');

        $oldPath = UploadedFile::fake()->image('old.jpg')->store('products', 'public');

        $product = Product::query()->create([
            'name' => 'Old Name',
            'price' => 10.00,
            'stock_qty' => 2,
            'status' => Product::STATUS_DRAFT,
            'description' => null,
            'image_path' => $oldPath,
        ]);

        $response = $this->put(route('products.update', $product), [
            'name' => 'Updated Product',
            'price' => 15.50,
            'stock_qty' => 10,
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Updated description',
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]);

        $response->assertRedirect(route('products.show', $product));

        $product->refresh();

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::query()->create([
            'name' => 'Delete Me',
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
