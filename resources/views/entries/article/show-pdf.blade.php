<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <x-layouts.featured-image :image="$entry->image->first()" />

        <h1>{{ $entry->title }}</h1>

        <p>
            {{ $entry->teaser }}
        </p>

        <x-blocks :blocks="$entry->contentBuilder->collect()" />
    </body>
</html>
