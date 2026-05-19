@props([
    'id',
    'action',
    'title' => 'Delete item',
    'description' => 'This action cannot be undone.',
    'triggerLabel' => 'Delete',
    'confirmLabel' => 'Delete',
])

<div>
    <x-ui.button type="button" variant="danger" size="sm" data-dialog-open="{{ $id }}">
        {{ $triggerLabel }}
    </x-ui.button>

    <dialog id="{{ $id }}" class="fixed inset-0 m-auto w-[min(92vw,28rem)] rounded-3xl border border-border bg-card p-0 text-card-foreground shadow-2xl backdrop:bg-slate-950/55">
        <form method="post" action="{{ $action }}">
            @csrf
            @method('DELETE')

            <div class="p-6">
                <h2 class="text-xl font-bold tracking-tight">{{ $title }}</h2>
                <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ $description }}</p>
            </div>

            <div class="flex justify-end gap-2 border-t border-border bg-muted/50 p-4">
                <x-ui.button type="button" variant="outline" data-dialog-close="{{ $id }}">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="danger">
                    {{ $confirmLabel }}
                </x-ui.button>
            </div>
        </form>
    </dialog>
</div>
