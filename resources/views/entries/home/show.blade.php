@php
    /** @var CraftCms\Cms\Entry\Elements\SimplePage $entry */
@endphp

<x-layouts::app :entry="$entry">
    <x-prose>
        <x-markdown :text="$entry->body" />
    </x-prose>

    <x-partials.widget class="mt-8" heading="Latest Articles">
        <livewire:articles.new />
    </x-partials.widget>
</x-layouts::app>
