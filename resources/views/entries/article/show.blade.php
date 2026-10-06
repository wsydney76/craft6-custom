@props([
    'entry',
    'prev',
    'next',
])

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

        <x-latest-articles heading="More Latest Articles" :exclude="$entry" />
    </x-prose>

    @if ($prev || $next)
        <x-layouts.prev-next class="mt-12" :$prev :$next />
    @endif
</x-layouts::app>
