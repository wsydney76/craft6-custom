@props([
    /**@var\CraftCms\Cms\Entry\Elements\Entry*/'entry',
])

<div
    {{ $attributes->class(['my-8 rounded-md border-2 border-gray-500 bg-zinc-100 p-4 text-lg text-sm dark:bg-zinc-800']) }}
>
    Created by {{ $entry->author->name }},
    {{ $entry->postDate->format('F j, Y') }}
</div>
