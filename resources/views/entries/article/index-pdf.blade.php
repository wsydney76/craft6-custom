<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h1>{{ $entry->title }}</h1>

        <p>
            {{ $entry->teaser }}
        </p>

        <ul>
            @foreach ($articles as $article)
                <li>{{ $article->link }}</li>
            @endforeach
        </ul>
    </body>
</html>
