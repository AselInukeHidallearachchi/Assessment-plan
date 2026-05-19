@extends('layouts.app', ['title' => 'Create Product'])

@section('content')
    <x-ui.page-header
        title="Register Product"
        description="Create a catalog record through Laravel Form Request validation and ProductService normalization."
    >
        <x-ui.button href="{{ route('products.index') }}" variant="outline">Back to Board</x-ui.button>
    </x-ui.page-header>

    <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Catalog Intake</x-ui.card-title>
                <x-ui.card-description>Keep this data precise. The controller receives validated data only and sends it to the handler.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                @include('products._form')
            </x-ui.card-content>
        </x-ui.card>
    </form>
@endsection
