@props([
    'articleOfTheDay',
])

<x-articles.featured {{ $attributes }} :heading="\CraftCms\Cms\t('Article of the Day', category: 'site')" :article="$articleOfTheDay" />
