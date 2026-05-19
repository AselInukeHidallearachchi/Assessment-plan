@extends('layouts.app', ['title' => 'Create Product'])

@section('content')
    <x-ui.page-header
        title="Create Product"
        description="Add a product with clean validated data and optional image evidence."
    >
        <x-ui.button href="{{ route('products.index') }}" variant="outline">Back to Catalog</x-ui.button>
    </x-ui.page-header>

    <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Product Details</x-ui.card-title>
                <x-ui.card-description>Validation is handled by Laravel Form Requests so field errors are consistent and testable.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                @include('products._form')
            </x-ui.card-content>
        </x-ui.card>
    </form>
@endsection
