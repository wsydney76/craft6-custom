@props([
    'heading',
    'footer' => null,
])
<flux:card
    {{
        $attributes->merge([
            'variant' => 'soft',
            'size' => 'sm',
            'body' => 'divided',
        ])
    }}
>
    <flux:card.header class="bg-zinc-200 dark:bg-zinc-700">
        <flux:card.heading size="lg">{{ $heading }}</flux:card.heading>
    </flux:card.header>

    <flux:card.body>
        {{ $slot }}
    </flux:card.body>

    @if ($footer)
        <flux:card.footer>{{ $footer }}</flux:card.footer>
    @endif
</flux:card>
