<?php

namespace App\Handlers\Products;

use App\Models\Product;
use App\Services\ProductService;

class DeleteProductHandler
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function __invoke(Product $product): void
    {
        $this->productService->delete($product);
    }
}
