@extends('layouts.app', ['title' => 'Edit Product'])

@section('content')
    <x-ui.page-header
        title="Revise Product"
        description="Update catalog data while preserving validation, image handling, and archived-product business rules."
    >
        <x-ui.button href="{{ route('products.show', $product) }}" variant="outline">View Product</x-ui.button>
    </x-ui.page-header>

    <form method="post" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>{{ $product->name }}</x-ui.card-title>
                <x-ui.card-description>Controller stays thin, handler coordinates, service applies business logic.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                @include('products._form', ['product' => $product])
            </x-ui.card-content>
        </x-ui.card>
    </form>
@endsection
