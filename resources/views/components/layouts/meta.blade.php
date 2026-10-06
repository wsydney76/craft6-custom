@props([
    /**@var\CraftCms\Cms\Entry\Elements\Entry*/'entry',
])

<flux:card size="sm">
    Created by {{ $entry->author->name }}, {{ $entry->postDate->diffForHumans() }}.
</flux:card>
