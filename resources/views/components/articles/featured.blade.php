@php
    use Illuminate\Support\Str;
@endphp

@php
    /** @var CraftCms\Cms\Entry\Elements\Article $article */
@endphp

@props([
    'article',
    'heading' => \CraftCms\Cms\t('Featured Article',
    category:'site'),
])

@if ($article)
    <div {{ $attributes->class('max-w-none') }}>
        <x-prose>
            <h2>{{ $heading }}</h2>
        </x-prose>

        <x-partials.card
            class="mt-4"
            :href="$article->getUrl()"
            :heading="$article->title"
            :image="$article->image->eagerly()->first()"
            width="768"
        >
            <flux:text>{{ $article->teaser }}</flux:text>

            @if ($excerpt = $article->excerpt)
                <flux:text>{{ $excerpt }}</flux:text>
            @endif

            <flux:text>
                @if ($name = $article->author->fullName)
                    {{ $name }},
                @endif

                {{ $article->postDate->diffForHumans() }}
            </flux:text>
        </x-partials.card>
    </div>
@endif
