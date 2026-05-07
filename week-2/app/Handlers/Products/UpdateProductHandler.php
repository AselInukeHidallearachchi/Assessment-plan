<?php

namespace App\Handlers\Products;

use App\Models\Product;
use App\Services\ProductService;

class UpdateProductHandler
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function __invoke(Product $product, array $payload): Product
    {
        return $this->productService->update($product, $payload);
    }
}
