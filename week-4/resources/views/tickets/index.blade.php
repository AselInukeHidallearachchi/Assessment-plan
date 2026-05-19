@extends('layouts.app', ['title' => 'Tickets'])

@section('content')
    <div class="between" style="margin-bottom: 16px;">
        <div>
            <h1 style="margin:0;">Ticket Queue</h1>
            <div class="subtle">Track issues from intake to resolution.</div>
        </div>
        <a class="btn btn-primary" href="{{ route('tickets.create') }}">New Ticket</a>
    </div>

    <section class="grid">
        <div class="stat"><span class="subtle">Open</span><strong>{{ $stats['open'] }}</strong></div>
        <div class="stat"><span class="subtle">In Progress</span><strong>{{ $stats['in_progress'] }}</strong></div>
        <div class="stat"><span class="subtle">Urgent</span><strong>{{ $stats['urgent'] }}</strong></div>
        <div class="stat"><span class="subtle">Resolved</span><strong>{{ $stats['resolved'] }}</strong></div>
    </section>

    <form class="panel row" method="get" action="{{ route('tickets.index') }}" style="margin-bottom: 16px;">
        <input name="q" placeholder="Search title or requester" value="{{ request('q') }}" style="max-width: 280px;">
        <select name="status" style="max-width: 180px;">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <select name="priority" style="max-width: 180px;">
            <option value="">All priorities</option>
            @foreach ($priorities as $priority)
                <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ strtoupper($priority) }}</option>
            @endforeach
        </select>
        <button class="btn" type="submit">Filter</button>
        <a class="btn" href="{{ route('tickets.index') }}">Reset</a>
    </form>

    <section class="panel">
        <table>
            <thead>
            <tr>
                <th>Ticket</th>
                <th>Requester</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($tickets as $ticket)
                <tr>
                    <td>
                        <strong>{{ $ticket->title }}</strong>
                        <div class="subtle">{{ strtoupper($ticket->category) }}</div>
                    </td>
                    <td>{{ $ticket->requester_name }}</td>
                    <td><span class="badge">{{ strtoupper($ticket->priority) }}</span></td>
                    <td><span class="badge">{{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</span></td>
                    <td>{{ optional($ticket->due_date)->format('Y-m-d') ?: 'Not set' }}</td>
                    <td class="row">
                        <a class="btn" href="{{ route('tickets.show', $ticket) }}">View</a>
                        <a class="btn" href="{{ route('tickets.edit', $ticket) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="subtle">No tickets found.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top: 14px;">{{ $tickets->links() }}</div>
    </section>
@endsection
