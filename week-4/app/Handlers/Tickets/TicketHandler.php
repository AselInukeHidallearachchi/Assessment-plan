<?php

namespace App\Handlers\Tickets;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class TicketHandler
{
    public function __construct(private readonly TicketService $ticketService)
    {
    }

    public function dashboard(): array
    {
        return [
            'open' => Ticket::query()->where('status', Ticket::STATUS_OPEN)->count(),
            'in_progress' => Ticket::query()->where('status', Ticket::STATUS_IN_PROGRESS)->count(),
            'urgent' => Ticket::query()->where('priority', Ticket::PRIORITY_URGENT)->count(),
            'resolved' => Ticket::query()->where('status', Ticket::STATUS_RESOLVED)->count(),
        ];
    }

    public function list(array $filters): Paginator
    {
        return Ticket::query()
            ->select(['id', 'title', 'requester_name', 'category', 'priority', 'status', 'due_date', 'created_at'])
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['priority'] ?? null, fn (Builder $query, string $priority) => $query->where('priority', $priority))
            ->when($filters['q'] ?? null, function (Builder $query, string $keyword): void {
                $query->where(function (Builder $query) use ($keyword): void {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('requester_name', 'like', "%{$keyword}%")
                        ->orWhere('requester_email', 'like', "%{$keyword}%");
                });
            })
            ->latest('id')
            ->simplePaginate(10)
            ->withQueryString();
    }

    public function create(array $payload): Ticket
    {
        return $this->ticketService->create($payload);
    }

    public function show(Ticket $ticket): Ticket
    {
        return $ticket;
    }

    public function update(Ticket $ticket, array $payload): Ticket
    {
        return $this->ticketService->update($ticket, $payload);
    }

    public function delete(Ticket $ticket): void
    {
        $this->ticketService->delete($ticket);
    }
}
