<x-layouts::app :$entry size="lg">
    <x-partials.cards-wrapper>
        @foreach ($articles as $article)
            <x-partials.card
                :href="$article->getUrl()"
                :heading="$article->title"
                :image="$article->image->eagerly()->first()"
            >
                <flux:text>{{ $article->teaser }}</flux:text>
                <flux:text>
                    {{ $article->author->name }}, {{ $article->postDate->diffForHumans() }}
                </flux:text>
            </x-partials.card>
        @endforeach
    </x-partials.cards-wrapper>

    <div class="not-prose my-8">
        <flux:button variant="filled" size="xs" href="?format=pdf&page={{ request('page', 1) }}">
            {{ \CraftCms\Cms\t('Download PDF', category: 'site') }}
        </flux:button>
        <flux:button variant="filled" size="xs" href="?format=json&page={{ request('page', 1) }}">
            {{ \CraftCms\Cms\t('Download JSON', category: 'site') }}
        </flux:button>
        <flux:button variant="filled" size="xs" href="?format=twig&page={{ request('page', 1) }}">
            {{ \CraftCms\Cms\t('Render with Twig', category: 'site') }}
        </flux:button>
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</x-layouts::app>
