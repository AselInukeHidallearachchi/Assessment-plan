<?php

namespace App\Handlers\Products;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListProductsHandler
{
    public function __invoke(): LengthAwarePaginator
    {
        return Product::query()->latest('id')->paginate(10);
    }
}
