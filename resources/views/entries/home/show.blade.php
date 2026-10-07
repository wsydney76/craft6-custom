@php
    /** @var CraftCms\Cms\Entry\Elements\SimplePage $entry */
@endphp

<x-layouts::app :entry="$entry">
    <x-prose>
        <x-markdown :text="$entry->body" />

        <flux:card size="sm" variant="soft" body="divided" id="new-articles">
            <flux:card.header>
                <flux:card.heading size="lg">Latest Articles</flux:card.heading>
            </flux:card.header>
            <flux:card.body>
                <livewire:articles.new />
            </flux:card.body>
        </flux:card>
    </x-prose>
</x-layouts::app>
