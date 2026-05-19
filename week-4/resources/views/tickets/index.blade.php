@extends('layouts.app', ['title' => 'Tickets'])

@section('content')
    <x-ui.page-header
        title="Ticket Queue"
        description="Track support requests from intake to resolution with clear ownership and status visibility."
    >
        <x-ui.button href="{{ route('tickets.create') }}">New Ticket</x-ui.button>
    </x-ui.page-header>

    <section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat-card label="Open" :value="$stats['open']" tone="info" />
        <x-ui.stat-card label="In Progress" :value="$stats['in_progress']" tone="warning" />
        <x-ui.stat-card label="Urgent" :value="$stats['urgent']" tone="default" />
        <x-ui.stat-card label="Resolved" :value="$stats['resolved']" tone="success" />
    </section>

    <x-ui.card class="mb-6">
        <x-ui.card-content class="pt-6">
            <form class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_180px_180px_auto_auto]" method="get" action="{{ route('tickets.index') }}">
                <x-ui.input name="q" placeholder="Search title, requester, or email" value="{{ request('q') }}" />

                <x-ui.select name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ str($status)->replace('_', ' ')->headline() }}
                        </option>
                    @endforeach
                </x-ui.select>

                <x-ui.select name="priority">
                    <option value="">All priorities</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" @selected(request('priority') === $priority)>
                            {{ str($priority)->headline() }}
                        </option>
                    @endforeach
                </x-ui.select>

                <x-ui.button type="submit" variant="secondary">Filter</x-ui.button>
                <x-ui.button href="{{ route('tickets.index') }}" variant="outline">Reset</x-ui.button>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <x-ui.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="ticket-table">
                <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Requester</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due</th>
                    <th class="w-44">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>
                            <p class="font-bold text-foreground">{{ $ticket->title }}</p>
                            <p class="mt-1 text-sm text-muted-foreground">{{ $ticket->categoryLabel() }}</p>
                        </td>
                        <td>
                            <p class="font-medium text-foreground">{{ $ticket->requester_name }}</p>
                            <p class="mt-1 text-sm text-muted-foreground">{{ $ticket->requester_email }}</p>
                        </td>
                        <td>
                            <x-ui.badge variant="{{ $ticket->priorityBadgeVariant() }}">{{ $ticket->priorityLabel() }}</x-ui.badge>
                        </td>
                        <td>
                            <x-ui.badge variant="{{ $ticket->statusBadgeVariant() }}">{{ $ticket->statusLabel() }}</x-ui.badge>
                        </td>
                        <td class="text-sm text-muted-foreground">
                            {{ optional($ticket->due_date)->format('Y-m-d') ?: 'Not set' }}
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <x-ui.button href="{{ route('tickets.show', $ticket) }}" variant="outline" size="sm">View</x-ui.button>
                                <x-ui.button href="{{ route('tickets.edit', $ticket) }}" variant="secondary" size="sm">Edit</x-ui.button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-sm text-muted-foreground">
                            No tickets found. Create a new ticket or reset the filters.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-border px-6 py-4">
            {{ $tickets->links() }}
        </div>
    </x-ui.card>
@endsection
