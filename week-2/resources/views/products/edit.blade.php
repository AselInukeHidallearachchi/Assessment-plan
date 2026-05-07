@extends('layouts.app', ['title' => 'Edit Product'])

@section('content')
    <h1>Edit Product</h1>
    <div class="card">
        <form method="post" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')
            @include('products._form', ['product' => $product])
        </form>
    </div>
@endsection
