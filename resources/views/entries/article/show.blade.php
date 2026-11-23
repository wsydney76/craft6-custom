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

        <div class="not-prose my-8">
            <flux:button variant="filled" size="xs" href="?format=pdf">{{ \CraftCms\Cms\t('Download PDF', category: 'site') }}</flux:button>
            <flux:button variant="filled" size="xs" href="?format=json">{{ \CraftCms\Cms\t('Download JSON', category: 'site') }}</flux:button>
            <flux:button variant="filled" size="xs" href="?format=twig">
                {{ \CraftCms\Cms\t('Render with Twig', category: 'site') }}
            </flux:button>
            @can('save', $entry)
                <flux:button variant="filled" size="xs" href="?format=livewire">
                    {{ \CraftCms\Cms\t('Edit with Livewire', category: 'site') }}
                </flux:button>
            @endcan
        </div>

        <x-latest-articles :heading="\CraftCms\Cms\t('More Latest Articles', category: 'site')" :exclude="$entry" />
    </x-prose>

    @if ($prev || $next)
        <x-layouts.prev-next class="mt-12" :$prev :$next />
    @endif
</x-layouts::app>
