@php
    $isEdit = isset($product);
@endphp

<div class="field">
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" required>
    @error('name') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="field" style="flex:1; min-width:220px;">
        <label for="price">Price</label>
        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required>
        @error('price') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="flex:1; min-width:220px;">
        <label for="stock_qty">Stock Quantity</label>
        <input id="stock_qty" name="stock_qty" type="number" min="0" value="{{ old('stock_qty', $product->stock_qty ?? 0) }}" required>
        @error('stock_qty') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="status">Status</label>
    <select id="status" name="status" required>
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(old('status', $product->status ?? 'draft') === $status)>
                {{ strtoupper($status) }}
            </option>
        @endforeach
    </select>
    @error('status') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="row">
    <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update Product' : 'Create Product' }}</button>
    <a class="btn" href="{{ route('products.index') }}">Cancel</a>
</div>
