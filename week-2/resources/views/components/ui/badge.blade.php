@props(['variant' => 'default'])

@php
    $classes = [
        'default' => 'border-stone-950 bg-amber-400 text-stone-950',
        'secondary' => 'border-stone-500 bg-stone-800 text-stone-100',
        'outline' => 'border-stone-400 text-stone-800',
        'success' => 'border-emerald-800 bg-emerald-200 text-emerald-950',
        'warning' => 'border-amber-700 bg-amber-200 text-amber-950',
        'danger' => 'border-red-800 bg-red-200 text-red-950',
        'info' => 'border-sky-800 bg-sky-200 text-sky-950',
    ][$variant] ?? 'border-stone-950 bg-amber-400 text-stone-950';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center border px-2.5 py-0.5 text-xs font-black uppercase tracking-[0.12em] {$classes}"]) }}>
    {{ $slot }}
</span>
