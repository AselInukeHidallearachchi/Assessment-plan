@extends('layouts.app', ['title' => 'Products'])

@section('content')
    <x-ui.page-header
        title="Inventory Board"
        description="A production-grade Laravel CRUD module for product records, server-side validation, and clear business flow."
    >
        <x-ui.button href="{{ route('products.create') }}">Register Product</x-ui.button>
    </x-ui.page-header>

    <x-ui.card class="overflow-hidden bg-transparent shadow-none">
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
                                <img class="h-16 w-16 border border-amber-950/30 object-cover shadow-[5px_5px_0_rgba(120,53,15,0.18)]" src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <span class="inline-flex h-16 w-16 items-center justify-center border border-dashed border-amber-900/45 bg-[#f4e4c4] text-center text-xs font-black uppercase tracking-wide text-stone-600">No image</span>
                            @endif
                        </td>
                        <td>
                            <p class="text-lg font-black text-stone-950">{{ $product->name }}</p>
                            <p class="mt-1 text-sm font-medium text-stone-600">{{ str($product->description ?: 'No description')->limit(54) }}</p>
                        </td>
                        <td class="text-lg font-black text-stone-950">{{ $product->formattedPrice() }}</td>
                        <td class="font-bold text-stone-700">{{ $product->stock_qty }}</td>
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
                        <td colspan="6" class="text-center text-sm font-bold text-stone-600">
                            No products found. Add your first catalog item to start the module demo.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 border border-amber-950/20 bg-card-foreground px-6 py-4 text-card-foreground">
            {{ $products->links() }}
        </div>
    </x-ui.card>
@endsection
