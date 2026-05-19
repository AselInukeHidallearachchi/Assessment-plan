@props([
    'label',
    'value',
    'tone' => 'default',
])

@php
    $toneClasses = [
        'default' => 'from-slate-50 to-white text-slate-900',
        'info' => 'from-sky-50 to-white text-sky-900',
        'warning' => 'from-amber-50 to-white text-amber-900',
        'success' => 'from-emerald-50 to-white text-emerald-900',
    ][$tone] ?? 'from-slate-50 to-white text-slate-900';
@endphp

<x-ui.card class="bg-gradient-to-br {{ $toneClasses }}">
    <div class="p-5">
        <p class="text-sm font-medium text-muted-foreground">{{ $label }}</p>
        <p class="mt-2 text-4xl font-black tracking-tight">{{ $value }}</p>
    </div>
</x-ui.card>
