<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_ticket(): void
    {
        $response = $this->post(route('tickets.store'), $this->payload());

        $response->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'title' => 'Cannot access billing dashboard',
            'requester_email' => 'asel@example.com',
            'status' => Ticket::STATUS_OPEN,
        ]);
    }

    public function test_validation_fails_for_short_title(): void
    {
        $response = $this->from(route('tickets.create'))->post(route('tickets.store'), [
            ...$this->payload(),
            'title' => 'Bad',
        ]);

        $response->assertRedirect(route('tickets.create'));
        $response->assertSessionHasErrors(['title']);
    }

    public function test_create_form_shows_laravel_field_errors(): void
    {
        $this->get(route('tickets.create'))
            ->assertOk()
            ->assertSee('novalidate', false);

        $this->followingRedirects()
            ->from(route('tickets.create'))
            ->post(route('tickets.store'), [])
            ->assertOk()
            ->assertSee('The title field is required.')
            ->assertSee('The requester name field is required.')
            ->assertSee('The requester email field is required.')
            ->assertSee('The description field is required.');
    }

    public function test_can_create_ticket_with_attachment(): void
    {
        Storage::fake('public');

        $response = $this->post(route('tickets.store'), [
            ...$this->payload(),
            'attachment' => UploadedFile::fake()->create('evidence.pdf', 120, 'application/pdf'),
        ]);

        $response->assertRedirect();

        $ticket = Ticket::query()->firstOrFail();

        $this->assertNotNull($ticket->attachment_path);
        Storage::disk('public')->assertExists($ticket->attachment_path);
    }

    public function test_can_update_ticket_status(): void
    {
        $ticket = Ticket::query()->create($this->payload());

        $response = $this->put(route('tickets.update', $ticket), [
            ...$this->payload(),
            'status' => Ticket::STATUS_RESOLVED,
            'resolution_note' => 'Access was restored by refreshing user permissions.',
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => Ticket::STATUS_RESOLVED,
        ]);
    }

    public function test_can_filter_tickets_by_status(): void
    {
        Ticket::query()->create($this->payload(['title' => 'Open ticket']));
        Ticket::query()->create($this->payload([
            'title' => 'Resolved ticket',
            'status' => Ticket::STATUS_RESOLVED,
        ]));

        $response = $this->get(route('tickets.index', ['status' => Ticket::STATUS_RESOLVED]));

        $response->assertOk();
        $response->assertSee('Resolved ticket');
        $response->assertDontSee('Open ticket');
    }

    public function test_can_delete_ticket(): void
    {
        $ticket = Ticket::query()->create($this->payload());

        $response = $this->delete(route('tickets.destroy', $ticket));

        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    private function payload(array $overrides = []): array
    {
        return [
            'title' => 'Cannot access billing dashboard',
            'requester_name' => 'Asel',
            'requester_email' => 'asel@example.com',
            'category' => 'access',
            'priority' => Ticket::PRIORITY_HIGH,
            'status' => Ticket::STATUS_OPEN,
            'due_date' => now()->addDays(2)->format('Y-m-d'),
            'description' => 'The user cannot open the billing dashboard after login.',
            'resolution_note' => null,
            ...$overrides,
        ];
    }
}
