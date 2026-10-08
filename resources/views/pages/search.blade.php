<?php

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

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
    #[Validate('not_regex:/ or /', message: 'The "OR" operator must be uppercase.')]
    public string $search = '';

    // Title of the underlying entry element.
    // Do not define a public `Entry $entry` property, as Craft's elements are not serializable
    // and will throw an error when Livewire attempts to serialize the component state.
    public string $title;

    // Pull in the underlying entry element.
    // The function signature must be exactly as this,
    // otherwise #[CurrentElement] will not work
    public function mount(#[CurrentElement] ?Entry $entry = null): void
    {
        $this->title = $entry?->title;
        $this->validate();
    }

    #[Computed]
    public function entries()
    {
        $search = trim($this->search);
        return Entry::find()
            ->section('article')
            ->when($search, fn ($query) => $query->search('title:' . $search))
            ->orderBy('score')
            ->withCustomFields(false)
            ->paginate(12);
    }

    // Set the page title passed to layout dynamically to the underlying entry's title.
    public function render()
    {
        return $this->view()->title($this->title);
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
            label="Search for:"
            type="search"
            placeholder="Search in title..."
            icon="magnifying-glass"
            wire:model.live.debounce.250ms="search"
        />

        <ul>
            @forelse ($this->entries as $entry)
                <li>{{ $entry->link }}</li>
            @empty
                <li>No entries found.</li>
            @endforelse
        </ul>

        {{ $this->entries->links() }}
    </flux:card.body>
</flux:card>
