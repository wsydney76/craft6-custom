@php
    /** @var CraftCms\Cms\Entry\Elements\SimplePage $entry */
    /** @var CraftCms\Cms\Entry\Elements\Article $articleOfTheDay */
@endphp

<x-layouts::app :entry="$entry">
    <x-prose>
        <x-markdown :text="$entry->body" />
    </x-prose>

    <x-articles.featured class="mt-8" heading="Article of the Day" :article="$articleOfTheDay" />

    <x-partials.widget class="mt-8" heading="Latest Articles">
        <livewire:articles.new />
    </x-partials.widget>
</x-layouts::app>
