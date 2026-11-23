<?php

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

use function CraftCms\Cms\t;

/**
 * A Livewire example full-page component that provides a search interface for Craft entries.
 *
 * Setup via a named route in `routes/web.php`:
 *
 * Route::livewire('/search', 'pages::search')->name('search');
 *
 * In Craft's section settings (single), set the "URI" to `search` and the "Route" to `search`.
 *
 * URI, domain, and HTTP method in named route and Craft's settings must match.
 *
 */
new class extends Component {
    use WithPagination;

    #[Url]
    #[Validate('not_regex:/ or /')]
    public string $search = '';

    protected function messages(): array
    {
        return [
            'search.not_regex' => t('The "OR" operator must be uppercase.', category: 'site'),
        ];
    }

    public function mount(): void
    {
        $this->validate();
    }

    #[Computed]
    public function entries()
    {
        $search = trim($this->search);
        return Entry::find()
            ->section('article')
            ->when($search, fn ($query) => $query->search($search))
            ->orderBy('score')
            ->withCustomFields(false)
            ->paginate(12);
    }

    // Goto first page when search term changes
    public function updatedSearch(): void
    {
        $this->resetPage();
    }
};
?>

{{--
    The Flux card component just adds some styling, but you can use any HTML you want here.
--}}

<div class="space-y-8">
    <flux:card variant="soft" body="divided">
        <flux:card.body class="space-y-4">
            {{--
                wire:model provides two-way binding to the reactive search property, updating it as you type.
                The Flux input component wraps a standard input and adds support for labels, icons, loading indicators, and error messages,
                while providing styling consistent with the Flux design system, including dark mode.
                You can use standard HTML elements instead if you prefer,
                and handle validation errors using Laravel's standard validation approach.
            --}}
            <flux:input
                :label="t('Search for:', category: 'site')"
                type="search"
                :placeholder="t('Enter search term...', category: 'site')"
                icon="magnifying-glass"
                wire:model.live.debounce.250ms="search"
            />

            <ul>
                @forelse ($this->entries as $entry)
                    <li>
                        <a href="{{ $entry->url }}#:~:text={{ $this->search }}">
                            {{ $entry->title }}
                        </a>
                    </li>
                @empty
                    <li>{{ t('No entries found.', category: 'site') }}</li>
                @endforelse
            </ul>

            {{ $this->entries->links(data: ['scrollTo' => false]) }}
        </flux:card.body>
    </flux:card>
</div>
