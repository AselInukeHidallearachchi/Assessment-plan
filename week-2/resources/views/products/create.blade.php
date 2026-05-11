@extends('layouts.app', ['title' => 'Create Product'])

@section('content')
    <h1>Create Product</h1>
    <div class="card">
        <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('products._form')
        </form>
    </div>
@endsection
