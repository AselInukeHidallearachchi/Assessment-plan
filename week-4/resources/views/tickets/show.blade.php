@extends('layouts.app', ['title' => 'Ticket Details'])

@section('content')
    <div class="between" style="margin-bottom: 16px;">
        <div>
            <h1 style="margin:0;">{{ $ticket->title }}</h1>
            <div class="subtle">{{ $ticket->requester_name }} · {{ $ticket->requester_email }}</div>
        </div>
        <div class="row">
            <a class="btn" href="{{ route('tickets.edit', $ticket) }}">Edit</a>
            <a class="btn" href="{{ route('tickets.index') }}">Back</a>
        </div>
    </div>

    <section class="panel">
        <p><strong>Category:</strong> {{ strtoupper($ticket->category) }}</p>
        <p><strong>Priority:</strong> {{ strtoupper($ticket->priority) }}</p>
        <p><strong>Status:</strong> {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</p>
        <p><strong>Due Date:</strong> {{ optional($ticket->due_date)->format('Y-m-d') ?: 'Not set' }}</p>
        <p><strong>Description:</strong></p>
        <p>{{ $ticket->description }}</p>
        <p><strong>Resolution Note:</strong></p>
        <p>{{ $ticket->resolution_note ?: 'No resolution note yet.' }}</p>

        @if ($ticket->attachment_path)
            <p><a class="btn" href="{{ asset('storage/'.$ticket->attachment_path) }}" target="_blank">Open Attachment</a></p>
        @endif

        <form method="post" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Delete this ticket?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">Delete Ticket</button>
        </form>
    </section>
@endsection
