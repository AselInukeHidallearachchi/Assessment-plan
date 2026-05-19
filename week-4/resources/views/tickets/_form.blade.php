<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-ui.label for="title">Title</x-ui.label>
        <x-ui.input id="title" name="title" value="{{ old('title', $ticket->title) }}" :invalid="$errors->has('title')" aria-describedby="title-error" autofocus />
        @error('title') <x-ui.field-error id="title-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="requester_name">Requester Name</x-ui.label>
        <x-ui.input id="requester_name" name="requester_name" value="{{ old('requester_name', $ticket->requester_name) }}" :invalid="$errors->has('requester_name')" aria-describedby="requester-name-error" />
        @error('requester_name') <x-ui.field-error id="requester-name-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="requester_email">Requester Email</x-ui.label>
        <x-ui.input id="requester_email" name="requester_email" type="email" value="{{ old('requester_email', $ticket->requester_email) }}" :invalid="$errors->has('requester_email')" aria-describedby="requester-email-error" />
        @error('requester_email') <x-ui.field-error id="requester-email-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="category">Category</x-ui.label>
        <x-ui.select id="category" name="category" :invalid="$errors->has('category')" aria-describedby="category-error">
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $ticket->category) === $category)>
                    {{ str($category)->headline() }}
                </option>
            @endforeach
        </x-ui.select>
        @error('category') <x-ui.field-error id="category-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="priority">Priority</x-ui.label>
        <x-ui.select id="priority" name="priority" :invalid="$errors->has('priority')" aria-describedby="priority-error">
            @foreach ($priorities as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $ticket->priority) === $priority)>
                    {{ str($priority)->headline() }}
                </option>
            @endforeach
        </x-ui.select>
        @error('priority') <x-ui.field-error id="priority-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="status">Status</x-ui.label>
        <x-ui.select id="status" name="status" :invalid="$errors->has('status')" aria-describedby="status-error">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $ticket->status) === $status)>
                    {{ str($status)->replace('_', ' ')->headline() }}
                </option>
            @endforeach
        </x-ui.select>
        @error('status') <x-ui.field-error id="status-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="due_date">Due Date</x-ui.label>
        <x-ui.input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($ticket->due_date)->format('Y-m-d')) }}" :invalid="$errors->has('due_date')" aria-describedby="due-date-error" />
        @error('due_date') <x-ui.field-error id="due-date-error">{{ $message }}</x-ui.field-error> @enderror
    </div>

    <div>
        <x-ui.label for="attachment">Attachment</x-ui.label>
        <x-ui.input id="attachment" name="attachment" type="file" :invalid="$errors->has('attachment')" aria-describedby="attachment-help attachment-error" />
        <p id="attachment-help" class="mt-2 text-xs text-muted-foreground">Allowed: PDF, JPG, PNG, WEBP, TXT. Maximum size: 4MB.</p>
        @error('attachment') <x-ui.field-error id="attachment-error">{{ $message }}</x-ui.field-error> @enderror
    </div>
</div>

<div class="mt-5">
    <x-ui.label for="description">Description</x-ui.label>
    <x-ui.textarea id="description" name="description" rows="5" :invalid="$errors->has('description')" aria-describedby="description-error">{{ old('description', $ticket->description) }}</x-ui.textarea>
    @error('description') <x-ui.field-error id="description-error">{{ $message }}</x-ui.field-error> @enderror
</div>

<div class="mt-5">
    <x-ui.label for="resolution_note">Resolution Note</x-ui.label>
    <x-ui.textarea id="resolution_note" name="resolution_note" rows="3" :invalid="$errors->has('resolution_note')" aria-describedby="resolution-note-error">{{ old('resolution_note', $ticket->resolution_note) }}</x-ui.textarea>
    @error('resolution_note') <x-ui.field-error id="resolution-note-error">{{ $message }}</x-ui.field-error> @enderror
</div>

<div class="mt-6 flex flex-wrap items-center gap-2">
    <x-ui.button type="submit">Save Ticket</x-ui.button>
    <x-ui.button href="{{ route('tickets.index') }}" variant="outline">Cancel</x-ui.button>
</div>
