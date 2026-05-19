<?php

namespace App\Services;

use App\Exceptions\ProductOperationException;
use App\Models\Product;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductService
{
    public function create(array $attributes): Product
    {
        $payload = $this->normalize($attributes);
        $image = Arr::pull($payload, 'image');

        try {
            if ($image instanceof UploadedFile) {
                $payload['image_path'] = $this->storeImage($image);
            }

            return Product::create($payload);
        } catch (Throwable $exception) {
            $this->deleteImage($payload['image_path'] ?? null);

            throw ProductOperationException::forAction('create', $exception);
        }
    }

    public function update(Product $product, array $attributes): Product
    {
        $payload = $this->normalize($attributes);
        $image = Arr::pull($payload, 'image');

        $this->assertStatusTransitionIsAllowed($product, (string) $payload['status']);

        try {
            if ($image instanceof UploadedFile) {
                $payload['image_path'] = $this->storeImage($image);
            }

            $previousImagePath = $product->image_path;

            $product->update($payload);

            if ($image instanceof UploadedFile) {
                $this->deleteImage($previousImagePath);
            }

            return $product->refresh();
        } catch (Throwable $exception) {
            if (isset($payload['image_path'])) {
                $this->deleteImage($payload['image_path']);
            }

            throw ProductOperationException::forAction('update', $exception);
        }
    }

    public function delete(Product $product): void
    {
        try {
            $this->deleteImage($product->image_path);
            $product->delete();
        } catch (Throwable $exception) {
            throw ProductOperationException::forAction('delete', $exception);
        }
    }

    private function normalize(array $attributes): array
    {
        $attributes['name'] = trim((string) $attributes['name']);
        $attributes['description'] = isset($attributes['description']) && $attributes['description'] !== ''
            ? trim((string) $attributes['description'])
            : null;

        return $attributes;
    }

    private function storeImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path !== null) {
            Storage::disk('public')->delete($path);
        }
    }

    private function assertStatusTransitionIsAllowed(Product $product, string $nextStatus): void
    {
        if ($product->status === Product::STATUS_ARCHIVED && $nextStatus !== Product::STATUS_ARCHIVED) {
            throw new DomainException('Archived products cannot be moved back to active or draft.');
        }
    }
}
