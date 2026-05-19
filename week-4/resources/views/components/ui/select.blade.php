@props(['invalid' => false])

@php
    $baseClasses = 'flex h-11 w-full rounded-xl border bg-background px-3 py-2 text-sm text-foreground shadow-sm transition focus-visible:outline-none focus-visible:ring-2 disabled:cursor-not-allowed disabled:opacity-50';
    $stateClasses = $invalid
        ? 'border-destructive focus-visible:ring-destructive'
        : 'border-input focus-visible:ring-ring';
@endphp

<select
    aria-invalid="{{ $invalid ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses}"]) }}
>
    {{ $slot }}
</select>
