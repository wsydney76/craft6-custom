@props([
    'entry' => null,
    'title' => null,
    'featured' => null,
    'size' => 'md',
    'editUrl' => null,
    'loadFilament' => false,
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

        @if ($loadFilament)
            @filamentStyles
        @endif

        <x-layouts.head :$loadFilament />
    </head>
    <body class="bg-zinc-50 font-sans text-zinc-800 dark:bg-zinc-900 dark:text-zinc-100">
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-zinc-900 focus:shadow-lg"
        >
            Skip to content
        </a>

        <header class="mx-auto mb-8 max-w-5xl py-2">
            <x-layouts.nav />

            @if ($featured)
                {{ $featured }}
            @elseif ($entry && $entry->image)
                <x-layouts.featured-image :image="$entry->image->first()" />
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
            <x-prose class="flex max-w-none justify-between">
                <h1>{{ $title }}</h1>
                <div id="titleslot"></div>
            </x-prose>

            @if ($slot->isNotEmpty())
                <div {{ $attributes }}>
                    {{ $slot }}
                </div>
            @endif
        </main>

        <x-layouts.footer :$editUrl class="mx-auto mt-8 max-w-5xl border-t border-gray-500 px-4" />
        @if ($loadFilament)
            @filamentScripts
        @endif
    </body>
</html>
