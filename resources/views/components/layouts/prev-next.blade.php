@props([
    'prev',
    'next',
])

@php
    use CraftCms\Cms\Entry\Elements\Entry;
    /** @var Entry|null $prev */
    /** @var Entry|null $next */
@endphp

<nav
    {{ $attributes->class(['flex justify-between gap-6 border-t border-zinc-400 pt-6']) }}
    aria-label="Article navigation"
>
    @if ($prev)
        <a href="{{ $prev->getUrl() }}" class="inline-flex items-center gap-2 hover:underline">
            <flux:icon.arrow-left class="size-4" aria-hidden="true" />
            <span>{{ $prev->title }}</span>
        </a>
    @else
        <span></span>
    @endif

    @if ($next)
        <a
            href="{{ $next->getUrl() }}"
            class="ml-auto inline-flex items-center gap-2 text-right hover:underline"
        >
            <span>{{ $next->title }}</span>
            <flux:icon.arrow-right class="size-4" aria-hidden="true" />
        </a>
    @endif
</nav>
