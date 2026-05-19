@extends('layouts.app', ['title' => 'Ticket Details'])

@section('content')
    <x-ui.page-header
        :title="$ticket->title"
        description="{{ $ticket->requester_name }} · {{ $ticket->requester_email }}"
    >
        <x-ui.button href="{{ route('tickets.edit', $ticket) }}" variant="secondary">Edit</x-ui.button>
        <x-ui.button href="{{ route('tickets.index') }}" variant="outline">Back</x-ui.button>
    </x-ui.page-header>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Request Details</x-ui.card-title>
                <x-ui.card-description>The main issue description and resolution notes.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-6">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wide text-muted-foreground">Description</h3>
                    <p class="mt-2 whitespace-pre-line leading-7 text-foreground">{{ $ticket->description }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wide text-muted-foreground">Resolution Note</h3>
                    <p class="mt-2 whitespace-pre-line leading-7 text-foreground">{{ $ticket->resolution_note ?: 'No resolution note yet.' }}</p>
                </div>

                @if ($ticket->attachment_path)
                    <x-ui.button href="{{ asset('storage/'.$ticket->attachment_path) }}" target="_blank" variant="outline">
                        Open Attachment
                    </x-ui.button>
                @endif
            </x-ui.card-content>
        </x-ui.card>

        <aside class="space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Ticket Metadata</x-ui.card-title>
                    <x-ui.card-description>Fast summary for review and handover.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-muted-foreground">Category</span>
                        <x-ui.badge variant="outline">{{ $ticket->categoryLabel() }}</x-ui.badge>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-muted-foreground">Priority</span>
                        <x-ui.badge variant="{{ $ticket->priorityBadgeVariant() }}">{{ $ticket->priorityLabel() }}</x-ui.badge>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-muted-foreground">Status</span>
                        <x-ui.badge variant="{{ $ticket->statusBadgeVariant() }}">{{ $ticket->statusLabel() }}</x-ui.badge>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-muted-foreground">Due Date</span>
                        <span class="text-sm font-semibold text-foreground">{{ optional($ticket->due_date)->format('Y-m-d') ?: 'Not set' }}</span>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card class="border-destructive/30">
                <x-ui.card-header>
                    <x-ui.card-title>Danger Zone</x-ui.card-title>
                    <x-ui.card-description>Deleting a ticket removes the record and its attachment.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <form method="post" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Delete this ticket?');">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="danger" class="w-full">Delete Ticket</x-ui.button>
                    </form>
                </x-ui.card-content>
            </x-ui.card>
        </aside>
    </div>
@endsection
