@props(['invalid' => false])

@php
    $baseClasses = 'flex h-12 w-full border bg-[#fffaf0] px-3 py-2 text-sm font-semibold text-stone-950 shadow-[inset_4px_4px_0_rgba(120,53,15,0.08)] transition focus-visible:outline-none focus-visible:ring-2 disabled:cursor-not-allowed disabled:opacity-50';
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
