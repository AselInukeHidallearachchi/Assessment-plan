@extends('layouts.app', ['title' => 'Edit Ticket'])

@section('content')
    <h1>Edit Ticket</h1>
    <form class="panel" method="post" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('tickets._form')
    </form>
@endsection
