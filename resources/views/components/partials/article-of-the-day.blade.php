@props([
    'articleOfTheDay',
])

<x-articles.featured {{ $attributes }} heading="Article of the Day" :article="$articleOfTheDay" />
