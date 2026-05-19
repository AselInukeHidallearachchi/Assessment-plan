@extends('layouts.app', ['title' => 'Create Ticket'])

@section('content')
    <h1>Create Ticket</h1>
    <form class="panel" method="post" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
        @csrf
        @include('tickets._form')
    </form>
@endsection
