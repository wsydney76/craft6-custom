@props([
    'variant' => 'success',
    'heading' => null,
    'text',
])

@php
    $icon = match ($variant) {
        'success' => 'check-circle',
        'danger' => 'exclamation-triangle',
        default => null,
    };
@endphp

<flux:callout :$icon :$variant>
    <flux:callout.text>
        {{ $text }}
    </flux:callout.text>
</flux:callout>
