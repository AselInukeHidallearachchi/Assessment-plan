@extends('layouts.app', ['title' => 'Product Details'])

@section('content')
    <x-ui.page-header
        :title="$product->name"
        description="Product details rendered through the layered Week 2 catalog module."
    >
        <x-ui.button href="{{ route('products.edit', $product) }}" variant="secondary">Edit</x-ui.button>
        <x-ui.button href="{{ route('products.index') }}" variant="outline">Back</x-ui.button>
    </x-ui.page-header>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Catalog Record</x-ui.card-title>
                <x-ui.card-description>The saved product information after request validation and service normalization.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-6">
                @if ($product->image_path)
                    <img class="max-h-96 w-full border border-amber-950/30 object-cover shadow-[10px_10px_0_rgba(120,53,15,0.22)]" src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}">
                @else
                    <div class="flex h-64 items-center justify-center border border-dashed border-amber-900/45 bg-[#f4e4c4] text-sm font-black uppercase tracking-[0.14em] text-stone-600">
                        No image uploaded
                    </div>
                @endif

                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.18em] text-stone-700">Description</h3>
                    <p class="mt-2 whitespace-pre-line text-base font-medium leading-7 text-stone-800">{{ $product->description ?: 'No description provided.' }}</p>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <aside class="space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Product Metadata</x-ui.card-title>
                    <x-ui.card-description>Quick values for review and viva explanation.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="flex items-center justify-between gap-4 border-b border-amber-950/10 pb-3">
                        <span class="text-sm text-muted-foreground">Price</span>
                        <span class="text-sm font-semibold text-foreground">{{ $product->formattedPrice() }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-amber-950/10 pb-3">
                        <span class="text-sm text-muted-foreground">Stock</span>
                        <span class="text-sm font-semibold text-foreground">{{ $product->stock_qty }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-muted-foreground">Status</span>
                        <x-ui.badge variant="{{ $product->statusBadgeVariant() }}">{{ $product->statusLabel() }}</x-ui.badge>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </aside>
    </div>
@endsection
