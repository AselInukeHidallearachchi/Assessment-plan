@extends('layouts.app', ['title' => 'Products'])

@section('content')
    <div class="between" style="margin-bottom: 12px;">
        <h1 style="margin:0;">Product Catalog</h1>
        <a class="btn btn-primary" href="{{ route('products.create') }}">Add Product</a>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format((float) $product->price, 2) }}</td>
                    <td>{{ $product->stock_qty }}</td>
                    <td>{{ strtoupper($product->status) }}</td>
                    <td class="row">
                        <a class="btn" href="{{ route('products.show', $product) }}">View</a>
                        <a class="btn" href="{{ route('products.edit', $product) }}">Edit</a>
                        <form method="post" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="muted">No products found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 12px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection
