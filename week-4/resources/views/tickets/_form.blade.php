<div class="form-grid">
    <div class="field">
        <label for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="requester_name">Requester Name</label>
        <input id="requester_name" name="requester_name" value="{{ old('requester_name', $ticket->requester_name) }}" required>
        @error('requester_name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="requester_email">Requester Email</label>
        <input id="requester_email" name="requester_email" type="email" value="{{ old('requester_email', $ticket->requester_email) }}" required>
        @error('requester_email') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="category">Category</label>
        <select id="category" name="category" required>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $ticket->category) === $category)>{{ strtoupper($category) }}</option>
            @endforeach
        </select>
        @error('category') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="priority">Priority</label>
        <select id="priority" name="priority" required>
            @foreach ($priorities as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $ticket->priority) === $priority)>{{ strtoupper($priority) }}</option>
            @endforeach
        </select>
        @error('priority') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $ticket->status) === $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="due_date">Due Date</label>
    <input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($ticket->due_date)->format('Y-m-d')) }}">
    @error('due_date') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="5" required>{{ old('description', $ticket->description) }}</textarea>
    @error('description') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="resolution_note">Resolution Note</label>
    <textarea id="resolution_note" name="resolution_note" rows="3">{{ old('resolution_note', $ticket->resolution_note) }}</textarea>
    @error('resolution_note') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="attachment">Attachment</label>
    <input id="attachment" name="attachment" type="file">
    @error('attachment') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="row">
    <button class="btn btn-primary" type="submit">Save Ticket</button>
    <a class="btn" href="{{ route('tickets.index') }}">Cancel</a>
</div>
