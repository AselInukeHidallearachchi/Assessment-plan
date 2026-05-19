@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'default',
    'size' => 'default',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full text-sm font-black uppercase tracking-[0.08em] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

    $variantClasses = [
        'default' => 'border border-amber-300 bg-amber-400 text-stone-950 shadow-[0_10px_0_#78350f] hover:-translate-y-0.5 hover:bg-amber-300',
        'secondary' => 'border border-stone-700 bg-stone-800 text-orange-50 hover:bg-stone-700',
        'outline' => 'border border-stone-600 bg-stone-950/30 text-orange-50 hover:border-amber-300 hover:text-amber-200',
        'ghost' => 'text-orange-50 hover:bg-stone-800',
        'danger' => 'border border-red-400 bg-red-600 text-red-50 shadow-[0_8px_0_#7f1d1d] hover:-translate-y-0.5 hover:bg-red-500',
    ][$variant] ?? 'border border-amber-300 bg-amber-400 text-stone-950 shadow-[0_10px_0_#78350f] hover:-translate-y-0.5 hover:bg-amber-300';

    $sizeClasses = [
        'default' => 'h-11 px-5 py-2',
        'sm' => 'h-9 px-3 text-xs',
        'lg' => 'h-12 px-7',
        'icon' => 'h-10 w-10',
    ][$size] ?? 'h-10 px-4 py-2';

    $classes = trim("{$baseClasses} {$variantClasses} {$sizeClasses}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
