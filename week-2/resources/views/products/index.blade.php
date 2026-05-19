@extends('layouts.app', ['title' => 'Products'])

@section('content')
    <x-ui.page-header
        title="Product Catalog"
        description="A Week 2 Laravel CRUD module with Form Requests, handler/service layering, and clean Blade UI."
    >
        <x-ui.button href="{{ route('products.create') }}">Add Product</x-ui.button>
    </x-ui.page-header>

    <x-ui.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="product-table">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="w-72">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            @if ($product->image_path)
                                <img class="h-14 w-14 rounded-xl border border-border object-cover" src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <span class="inline-flex h-14 w-14 items-center justify-center rounded-xl border border-dashed border-input bg-muted text-center text-xs font-medium text-muted-foreground">No image</span>
                            @endif
                        </td>
                        <td>
                            <p class="font-bold text-foreground">{{ $product->name }}</p>
                            <p class="mt-1 text-sm text-muted-foreground">{{ str($product->description ?: 'No description')->limit(54) }}</p>
                        </td>
                        <td class="font-semibold text-foreground">{{ $product->formattedPrice() }}</td>
                        <td class="text-muted-foreground">{{ $product->stock_qty }}</td>
                        <td>
                            <x-ui.badge variant="{{ $product->statusBadgeVariant() }}">{{ $product->statusLabel() }}</x-ui.badge>
                        </td>
                        <td class="whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <x-ui.button href="{{ route('products.show', $product) }}" variant="outline" size="sm">View</x-ui.button>
                                <x-ui.button href="{{ route('products.edit', $product) }}" variant="secondary" size="sm">Edit</x-ui.button>
                                <x-ui.delete-confirmation
                                    id="delete-product-{{ $product->id }}"
                                    action="{{ route('products.destroy', $product) }}"
                                    title="Delete product"
                                    description="This will permanently delete {{ $product->name }} from the catalog."
                                    triggerLabel="Delete"
                                    confirmLabel="Delete Product"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-sm text-muted-foreground">
                            No products found. Add your first catalog item to start the module demo.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-border px-6 py-4">
            {{ $products->links() }}
        </div>
    </x-ui.card>
@endsection
