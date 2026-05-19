<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => is_string($this->title) ? trim($this->title) : $this->title,
            'requester_name' => is_string($this->requester_name) ? trim($this->requester_name) : $this->requester_name,
            'requester_email' => is_string($this->requester_email) ? strtolower(trim($this->requester_email)) : $this->requester_email,
            'description' => is_string($this->description) ? trim($this->description) : $this->description,
            'resolution_note' => is_string($this->resolution_note) ? trim($this->resolution_note) : $this->resolution_note,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:160'],
            'requester_name' => ['required', 'string', 'min:2', 'max:120'],
            'requester_email' => ['required', 'email', 'max:160'],
            'category' => ['required', Rule::in(Ticket::categories())],
            'priority' => ['required', Rule::in(Ticket::priorities())],
            'status' => ['required', Rule::in(Ticket::statuses())],
            'due_date' => ['nullable', 'date'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'resolution_note' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,txt', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'attachment.max' => 'Attachment must not be larger than 4MB.',
            'attachment.mimes' => 'Attachment must be a PDF, image, or TXT file.',
        ];
    }
}
