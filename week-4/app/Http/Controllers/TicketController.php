<?php

namespace App\Http\Controllers;

use App\Handlers\Tickets\TicketHandler;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(private readonly TicketHandler $ticketHandler)
    {
    }

    public function index(Request $request): View
    {
        return view('tickets.index', [
            'tickets' => $this->ticketHandler->list($request->only(['status', 'priority', 'q'])),
            'stats' => $this->ticketHandler->dashboard(),
            'statuses' => Ticket::statuses(),
            'priorities' => Ticket::priorities(),
        ]);
    }

    public function create(): View
    {
        return view('tickets.create', [
            'ticket' => new Ticket(['status' => Ticket::STATUS_OPEN, 'priority' => Ticket::PRIORITY_MEDIUM]),
            'statuses' => Ticket::statuses(),
            'priorities' => Ticket::priorities(),
            'categories' => Ticket::categories(),
        ]);
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $ticket = $this->ticketHandler->create($request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket): View
    {
        return view('tickets.show', [
            'ticket' => $this->ticketHandler->show($ticket),
        ]);
    }

    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit', [
            'ticket' => $ticket,
            'statuses' => Ticket::statuses(),
            'priorities' => Ticket::priorities(),
            'categories' => Ticket::categories(),
        ]);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $updatedTicket = $this->ticketHandler->update($ticket, $request->validated());

        return redirect()
            ->route('tickets.show', $updatedTicket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->ticketHandler->delete($ticket);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}
