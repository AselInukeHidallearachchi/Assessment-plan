@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'mb-8 border-l-8 border-amber-400 bg-stone-950/48 p-6 shadow-[12px_12px_0_rgba(0,0,0,0.24)] md:flex md:items-end md:justify-between']) }}>
    <div>
        <h1 class="text-4xl font-black tracking-tight text-orange-50 md:text-5xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-3 max-w-2xl text-sm font-medium leading-6 text-stone-300">{{ $description }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="mt-5 flex flex-wrap items-center gap-2 md:mt-0">{{ $slot }}</div>
    @endif
</div>
