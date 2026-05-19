<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public function create(array $attributes): Ticket
    {
        $payload = $this->normalize($attributes);
        $attachment = Arr::pull($payload, 'attachment');

        if ($attachment instanceof UploadedFile) {
            $payload['attachment_path'] = $attachment->store('tickets', 'public');
        }

        return Ticket::create($payload);
    }

    public function update(Ticket $ticket, array $attributes): Ticket
    {
        $payload = $this->normalize($attributes);
        $attachment = Arr::pull($payload, 'attachment');

        if ($attachment instanceof UploadedFile) {
            $previousPath = $ticket->attachment_path;
            $payload['attachment_path'] = $attachment->store('tickets', 'public');
        }

        $ticket->update($payload);

        if (isset($previousPath)) {
            Storage::disk('public')->delete($previousPath);
        }

        return $ticket->refresh();
    }

    public function delete(Ticket $ticket): void
    {
        if ($ticket->attachment_path !== null) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }

        $ticket->delete();
    }

    private function normalize(array $attributes): array
    {
        $attributes['title'] = trim((string) $attributes['title']);
        $attributes['requester_name'] = trim((string) $attributes['requester_name']);
        $attributes['requester_email'] = strtolower(trim((string) $attributes['requester_email']));
        $attributes['description'] = trim((string) $attributes['description']);
        $attributes['resolution_note'] = isset($attributes['resolution_note']) && $attributes['resolution_note'] !== ''
            ? trim((string) $attributes['resolution_note'])
            : null;

        return $attributes;
    }
}
