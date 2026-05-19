@if ($slot->isNotEmpty())
    <p {{ $attributes->merge(['class' => 'mt-2 text-sm font-medium text-destructive']) }} role="alert">
        {{ $slot }}
    </p>
@endif
