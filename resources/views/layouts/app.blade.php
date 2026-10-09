@props([
    'entry' => null,
    'title' => null,
    'featured' => null,
    'size' => 'md',
])
@php
    $title ??= $entry->title ?? ($title ?? config('app.name'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scrollbar-gutter-stable">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $entry->title ?? ($title ?? config('app.name')) }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @fluxAppearance
        @livewireStyles
    </head>
    <body class="bg-zinc-50 font-sans text-zinc-800 dark:bg-zinc-900 dark:text-zinc-100">
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-zinc-900 focus:shadow-lg"
        >
            Skip to content
        </a>

        <header class="mx-auto mb-8 max-w-5xl pt-8">
            <livewire:layouts.nav />

            @if ($featured)
                {{ $featured }}
            @elseif ($entry && $entry->image)
                <div class="px-4 lg:px-0">
                    <x-img
                        :image="$entry->image->first()"
                        class="rounded-xl"
                        :sizes="[768, 480]"
                        width="1024"
                        height="400"
                    />
                </div>
            @endif
        </header>

        <main
            id="main-content"
            @class([
                'mx-auto px-4',
                'max-w-3xl' => $size === 'md',
                'max-w-5xl' => $size === 'lg',
            ])
        >
            <x-prose class="mb-8">
                <h1>{{ $title }}</h1>
            </x-prose>

            @if ($slot->isNotEmpty())
                <div {{ $attributes }}>
                    {{ $slot }}
                </div>
            @endif
        </main>

        <footer
            class="mx-auto mt-8 flex max-w-5xl justify-between border-t border-gray-500 px-4 py-6"
        >
            <div>&copy; {{ now()->format('Y') }}</div>
            <div>
                @if ($entry)
                    @can('save', $entry)
                        <a href="{{ $entry->cpEditUrl }}">Edit</a>
                    @endcan
                @endif
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
