<?php

namespace App\Handlers\Products;

use App\Models\Product;
use App\Services\ProductService;

class CreateProductHandler
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function __invoke(array $payload): Product
    {
        return $this->productService->create($payload);
    }
}
