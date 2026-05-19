@extends('layouts.app', ['title' => 'Edit Ticket'])

@section('content')
    <x-ui.page-header
        title="Edit Ticket"
        description="Update status, priority, evidence, or resolution details as the work progresses."
    >
        <x-ui.button href="{{ route('tickets.show', $ticket) }}" variant="outline">View Ticket</x-ui.button>
    </x-ui.page-header>

    <form method="post" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>{{ $ticket->title }}</x-ui.card-title>
                <x-ui.card-description>{{ $ticket->requester_name }} · {{ $ticket->requester_email }}</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                @include('tickets._form')
            </x-ui.card-content>
        </x-ui.card>
    </form>
@endsection
