<?php

namespace App\Services;

use App\Models\Product;
use DomainException;

class ProductService
{
    public function create(array $attributes): Product
    {
        return Product::create($this->normalize($attributes));
    }

    public function update(Product $product, array $attributes): Product
    {
        $payload = $this->normalize($attributes);

        $this->assertStatusTransitionIsAllowed($product, (string) $payload['status']);

        $product->update($payload);

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    private function normalize(array $attributes): array
    {
        $attributes['name'] = trim((string) $attributes['name']);
        $attributes['description'] = isset($attributes['description']) && $attributes['description'] !== ''
            ? trim((string) $attributes['description'])
            : null;

        return $attributes;
    }

    private function assertStatusTransitionIsAllowed(Product $product, string $nextStatus): void
    {
        if ($product->status === Product::STATUS_ARCHIVED && $nextStatus !== Product::STATUS_ARCHIVED) {
            throw new DomainException('Archived products cannot be moved back to active or draft.');
        }
    }
}
