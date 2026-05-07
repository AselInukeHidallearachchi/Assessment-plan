@extends('layouts.app', ['title' => 'Product Details'])

@section('content')
    <div class="between" style="margin-bottom: 12px;">
        <h1 style="margin:0;">{{ $product->name }}</h1>
        <div class="row">
            <a class="btn" href="{{ route('products.edit', $product) }}">Edit</a>
            <a class="btn" href="{{ route('products.index') }}">Back</a>
        </div>
    </div>

    <div class="card">
        <p><strong>Price:</strong> {{ number_format((float) $product->price, 2) }}</p>
        <p><strong>Stock:</strong> {{ $product->stock_qty }}</p>
        <p><strong>Status:</strong> {{ strtoupper($product->status) }}</p>
        <p><strong>Description:</strong> {{ $product->description ?: 'N/A' }}</p>
    </div>
@endsection
