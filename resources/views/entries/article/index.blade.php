<x-layouts::app :$entry size="lg">
    <x-partials.cards-wrapper>
        @foreach ($articles as $article)
            <x-partials.card
                :href="$article->getUrl()"
                :heading="$article->title"
                :image="$article->image->first()"
            >
                <flux:text>{{ $article->teaser }}</flux:text>
                <flux:text>
                    {{ $article->author->name }}, {{ $article->postDate->diffForHumans() }}
                </flux:text>
            </x-partials.card>
        @endforeach
    </x-partials.cards-wrapper>

    {{ $articles->links() }}
</x-layouts::app>
