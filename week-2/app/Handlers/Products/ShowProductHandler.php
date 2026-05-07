<?php

namespace App\Handlers\Products;

use App\Models\Product;

class ShowProductHandler
{
    public function __invoke(Product $product): Product
    {
        return $product;
    }
}
