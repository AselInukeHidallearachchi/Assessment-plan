@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between']) }}>
    <div>
        <h1 class="text-3xl font-black tracking-tight text-foreground">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 text-sm text-muted-foreground">{{ $description }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
