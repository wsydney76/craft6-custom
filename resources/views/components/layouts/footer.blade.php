@props([
    'copyright' => null,
    'articleOfTheDay' => null,
    'currentElement' => null,
])
<footer
    {{ $attributes->class(['grid grid-cols-3 border-t border-gray-500 py-4']) }}
>
    <div>
        {{ $copyright }}
        <br />
        All rights reserved
    </div>
    <div class="flex justify-center">
        @if ($currentElement)
            @can('save', $currentElement)
                <flux:button
                    variant="ghost"
                    size="xs"
                    icon="pencil-square"
                    tooltip="Edit this element"
                    href="{{ $currentElement->cpEditUrl }}"
                ></flux:button>
            @endcan
        @endif

        @guest
            <flux:button
                variant="ghost"
                size="xs"
                href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
            >
                Login
            </flux:button>
        @endguest
    </div>
    <div class="text-right">
        @if ($articleOfTheDay)
            Article of the day:
            <br />
            <a href="{{ $articleOfTheDay->url }}">
                {{ $articleOfTheDay->title }}
            </a>
        @endif
    </div>
</footer>
<flux:toast invert position="top center" />
@fluxScripts
