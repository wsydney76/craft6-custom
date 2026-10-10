@php
    use Illuminate\Support\Str;
@endphp

@php
    /** @var CraftCms\Cms\Entry\Elements\Article $article */
@endphp

@props([
    'article',
    'heading' => 'Featured Article',
])

@if ($article)
    <div {{ $attributes->class('prose max-w-none dark:prose-invert') }}>
        <h2>{{ $heading }}</h2>
        <x-partials.card
            class="not-prose"
            :href="$article->getUrl()"
            :heading="$article->title"
            :image="$article->image->eagerly()->first()"
            width="768"
        >
            <flux:text>{{ $article->teaser }}</flux:text>

            @if ($excerpt = $article->contentBuilder->type('text')->first())
                <flux:text>{{ Str::limit($excerpt->text, 200, preserveWords: true) }}</flux:text>
            @endif

            <flux:text>
                {{ $article->author->name }}, {{ $article->postDate->diffForHumans() }}
            </flux:text>
        </x-partials.card>
    </div>
@endif
