@php
    /** @var CraftCms\Cms\Entry\Elements\Article $entry */
@endphp

<x-layouts::app :entry="$entry">
    <x-prose>
        @if ($entry->teaser)
            <p class="text-lg font-semibold">{{ $entry->teaser }}</p>
        @endif

        <x-layouts.meta :entry="$entry" />

        <x-blocks :blocks="$entry->contentBuilder->collect()" />
    </x-prose>
</x-layouts::app>
