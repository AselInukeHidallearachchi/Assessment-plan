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

    <dialog id="{{ $id }}" class="fixed inset-0 m-auto w-[min(92vw,28rem)] border border-amber-900 bg-card p-0 text-card-foreground shadow-[16px_16px_0_rgba(120,53,15,0.42)] backdrop:bg-stone-950/70">
        <form method="post" action="{{ $action }}">
            @csrf
            @method('DELETE')

            <div class="border-b border-amber-950/20 bg-[#f4e4c4] p-6">
                <h2 class="text-xl font-bold tracking-tight">{{ $title }}</h2>
                <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ $description }}</p>
            </div>

            <div class="flex justify-end gap-2 bg-[#fff8ea] p-4">
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
