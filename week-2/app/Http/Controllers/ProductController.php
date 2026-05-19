<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductOperationException;
use App\Handlers\Products\ProductHandler;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductHandler $productHandler)
    {
    }

    public function index(): View
    {
        return view('products.index', [
            'products' => $this->productHandler->list(),
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'statuses' => Product::statuses(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            $product = $this->productHandler->create($request->validated());
        } catch (ProductOperationException $exception) {
            return $this->handleProductOperationException($exception);
        }

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $this->productHandler->show($product),
        ]);
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'statuses' => Product::statuses(),
        ]);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product,
    ): RedirectResponse {
        try {
            $updatedProduct = $this->productHandler->update($product, $request->validated());
        } catch (DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors(['status' => $exception->getMessage()]);
        } catch (ProductOperationException $exception) {
            return $this->handleProductOperationException($exception);
        }

        return redirect()
            ->route('products.show', $updatedProduct)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            $this->productHandler->delete($product);
        } catch (ProductOperationException $exception) {
            return $this->handleProductOperationException($exception);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function handleProductOperationException(ProductOperationException $exception): RedirectResponse
    {
        Log::error($exception->getMessage(), [
            'exception' => $exception,
        ]);

        return back()
            ->withInput()
            ->withErrors(['product' => $exception->getMessage()]);
    }
}
