<?php

namespace App\Handlers\Products;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\Pagination\Paginator;

class ProductHandler
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function list(): Paginator
    {
        return Product::query()
            ->select(['id', 'name', 'price', 'stock_qty', 'status', 'image_path', 'created_at'])
            ->latest('id')
            ->simplePaginate(10);
    }

    public function create(array $payload): Product
    {
        return $this->productService->create($payload);
    }

    public function show(Product $product): Product
    {
        return $product;
    }

    public function update(Product $product, array $payload): Product
    {
        return $this->productService->update($product, $payload);
    }

    public function delete(Product $product): void
    {
        $this->productService->delete($product);
    }
}
