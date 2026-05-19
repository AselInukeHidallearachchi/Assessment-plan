@php
    $isEdit = isset($product);
    $selectedStatus = old('status', $product->status ?? '');
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-ui.label for="name">Name</x-ui.label>
        <x-ui.input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" :invalid="$errors->has('name')" aria-describedby="name-error" autofocus />
        @error('name') <x-ui.field-error id="name-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="price">Price</x-ui.label>
        <x-ui.input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" :invalid="$errors->has('price')" aria-describedby="price-error" />
        @error('price') <x-ui.field-error id="price-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="stock_qty">Stock Quantity</x-ui.label>
        <x-ui.input id="stock_qty" name="stock_qty" type="number" min="0" value="{{ old('stock_qty', $product->stock_qty ?? '') }}" :invalid="$errors->has('stock_qty')" aria-describedby="stock-qty-error" />
        @error('stock_qty') <x-ui.field-error id="stock-qty-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="status">Status</x-ui.label>
        <x-ui.select id="status" name="status" :invalid="$errors->has('status')" aria-describedby="status-error">
            <option value="" @selected($selectedStatus === '')>Select status</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected($selectedStatus === $status)>
                    {{ str($status)->headline() }}
                </option>
            @endforeach
        </x-ui.select>
        @error('status') <x-ui.field-error id="status-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="image">Product Image</x-ui.label>
        <x-ui.input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" :invalid="$errors->has('image')" aria-describedby="image-help image-error" />
        <p id="image-help" class="mt-2 text-xs text-muted-foreground">Accepted: JPG, PNG, WEBP. Maximum size: 2MB.</p>
        @error('image') <x-ui.field-error id="image-error">{{ $message }}</x-ui.field-error> @enderror
    </div>
</div>

@if ($isEdit && $product->image_path)
    <div class="mt-5 border border-amber-950/20 bg-[#f4e4c4] p-4">
        <p class="mb-3 text-xs font-black uppercase tracking-[0.18em] text-stone-700">Current Image</p>
        <img class="h-32 w-32 border border-amber-950/30 object-cover shadow-[6px_6px_0_rgba(120,53,15,0.2)]" src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}">
    </div>
@endif

<div class="mt-5">
    <x-ui.label for="description">Description</x-ui.label>
    <x-ui.textarea id="description" name="description" rows="4" :invalid="$errors->has('description')" aria-describedby="description-error">{{ old('description', $product->description ?? '') }}</x-ui.textarea>
    @error('description') <x-ui.field-error id="description-error">{{ $message }}</x-ui.field-error> @enderror
</div>

<div class="mt-6 flex flex-wrap items-center gap-2">
    <x-ui.button type="submit">{{ $isEdit ? 'Update Product' : 'Create Product' }}</x-ui.button>
    <x-ui.button href="{{ route('products.index') }}" variant="outline">Cancel</x-ui.button>
</div>
