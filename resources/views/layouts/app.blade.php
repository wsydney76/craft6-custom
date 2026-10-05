@props([
    'entry' => null,
    'title' => null,
    'featured' => null,
    'meta' => null,
    'prose' => null,
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
        <header class="mx-auto mb-8 max-w-5xl pt-8">
            <nav class="flex items-center justify-between px-4 pb-6">
                <a class="text-2xl font-bold" href="/">{{ config('app.name') }}</a>
                <div class="flex items-center space-x-4">
                    <ul class="flex space-x-4">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                    </ul>
                    <x-layouts.dark-mode-switcher />
                </div>
            </nav>

            @if ($featured)
                {{ $featured }}
            @elseif ($entry && $entry->image)
                <div>
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

        <main class="mx-auto max-w-3xl px-4">
            <h1 class="mb-8 text-3xl font-bold">{{ $title }}</h1>

            @if ($meta)
                {{ $meta }}
            @elseif ($entry && $entry->author)
                <div class="my-8 rounded-md border-2 border-gray-500 bg-gray-100 p-6 text-lg">
                    Created by {{ $entry->author->name }},
                    {{ $entry->postDate->format('F j, Y') }}
                </div>
            @endif

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
                    <a href="{{ $entry->cpEditUrl }}">Edit</a>
                @endif
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
