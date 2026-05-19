@extends('layouts.app', ['title' => 'Create Ticket'])

@section('content')
    <x-ui.page-header
        title="Create Ticket"
        description="Capture the issue clearly so the next engineer can understand and resolve it quickly."
    >
        <x-ui.button href="{{ route('tickets.index') }}" variant="outline">Back to Queue</x-ui.button>
    </x-ui.page-header>

    <form method="post" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
        @csrf
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Ticket Details</x-ui.card-title>
                <x-ui.card-description>Use short, searchable wording for the title and detailed steps in the description.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                @include('tickets._form')
            </x-ui.card-content>
        </x-ui.card>
    </form>
@endsection
