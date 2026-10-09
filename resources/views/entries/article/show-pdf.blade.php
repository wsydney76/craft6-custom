<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h1>{{ $entry->title }}</h1>

        <p>
            {{ $entry->teaser }}
        </p>

        <x-blocks :blocks="$entry->contentBuilder->collect()" />
    </body>
</html>
