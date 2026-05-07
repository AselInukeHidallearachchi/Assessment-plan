<?php

namespace App\Http\Controllers;

use App\Handlers\Products\CreateProductHandler;
use App\Handlers\Products\DeleteProductHandler;
use App\Handlers\Products\ListProductsHandler;
use App\Handlers\Products\ShowProductHandler;
use App\Handlers\Products\UpdateProductHandler;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListProductsHandler $listProducts): View
    {
        return view('products.index', [
            'products' => $listProducts(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create', [
            'statuses' => Product::statuses(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request, CreateProductHandler $createProduct): RedirectResponse
    {
        $product = $createProduct($request->validated());

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, ShowProductHandler $showProduct): View
    {
        return view('products.show', [
            'product' => $showProduct($product),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'statuses' => Product::statuses(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateProductRequest $request,
        Product $product,
        UpdateProductHandler $updateProduct,
    ): RedirectResponse {
        try {
            $updatedProduct = $updateProduct($product, $request->validated());
        } catch (DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors(['status' => $exception->getMessage()]);
        }

        return redirect()
            ->route('products.show', $updatedProduct)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, DeleteProductHandler $deleteProduct): RedirectResponse
    {
        $deleteProduct($product);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
