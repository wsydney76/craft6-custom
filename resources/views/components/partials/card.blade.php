@props([
    'href',
    'image' => null,
    'heading',
])
<flux:card {{ $attributes->merge(['size' => 'sm']) }}>
    <flux:card.bleed>
        <a href="{{ $href }}">
            <x-img
                class="transition-transform hover:scale-105"
                :image="$image"
                width="450"
                height="200"
            />
        </a>
    </flux:card.bleed>
    <flux:card.body class="mt-4 space-y-2">
        <flux:heading size="lg">
            <a href="{{ $href }}" class="hover:underline">
                {{ $heading }}
            </a>
        </flux:heading>

        {{ $slot }}
    </flux:card.body>
</flux:card>
