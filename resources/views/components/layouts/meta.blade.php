@props([
    'entry',
])

@php
    /**@var\CraftCms\Cms\Entry\Elements\Entry*/ $entry;
@endphp

<flux:card size="sm">
    {{ \CraftCms\Cms\t('Created by {author}, {date}.', [
        'author' => $entry->author->fullName,
        'date' => $entry->postDate->diffForHumans(),
    ], category: 'site') }}
</flux:card>
