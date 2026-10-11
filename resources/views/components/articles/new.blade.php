<?php

use CraftCms\Cms\Entry\Elements\Entry;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    #[Computed]
    public function entries()
    {
        return Entry::find()
            ->section('article')
            ->latest('postDate')
            ->withCustomFields(false)
            ->simplePaginate(5);
    }
};
?>

<div>
    <ul class="space-y-1">
        @foreach ($this->entries as $entry)
            <li>
                <a class="hover:underline" href="{{ $entry->url }}">{{ $entry->title }}</a>
                ({{ $entry->postDate->diffForHumans() }})
            </li>
        @endforeach
    </ul>

    <div class="mt-4">
        {{ $this->entries->links(data: ['scrollTo' => false]) }}
    </div>
</div>
