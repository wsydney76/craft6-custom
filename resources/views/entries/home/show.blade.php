@php
    /** @var CraftCms\Cms\Entry\Elements\Home $entry */
@endphp

<x-layouts::app :entry="$entry">
    <x-prose>
        <x-markdown :text="$entry->body" />

        <x-latest-articles heading="Latest Articles" />
    </x-prose>
</x-layouts::app>
