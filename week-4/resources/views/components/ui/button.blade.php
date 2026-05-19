@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'default',
    'size' => 'default',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

    $variantClasses = [
        'default' => 'bg-primary text-primary-foreground shadow hover:bg-primary/90',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'danger' => 'bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90',
    ][$variant] ?? 'bg-primary text-primary-foreground shadow hover:bg-primary/90';

    $sizeClasses = [
        'default' => 'h-10 px-4 py-2',
        'sm' => 'h-9 rounded-lg px-3',
        'lg' => 'h-11 rounded-2xl px-6',
        'icon' => 'h-10 w-10',
    ][$size] ?? 'h-10 px-4 py-2';

    $classes = trim("{$baseClasses} {$variantClasses} {$sizeClasses}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
