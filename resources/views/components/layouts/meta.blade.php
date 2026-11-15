@props([
    'entry',
])

@php
    /**@var\CraftCms\Cms\Entry\Elements\Entry*/ $entry;
@endphp

<flux:card size="sm">
    Created by {{ $entry->author->fullName }}, {{ $entry->postDate->diffForHumans() }}.
</flux:card>
