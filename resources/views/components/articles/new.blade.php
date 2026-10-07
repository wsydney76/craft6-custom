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
            ->simplePaginate(5);
    }
};
?>

<div>
    <ul>
        @foreach ($this->entries as $entry)
            <li>{{ $entry->link }} ({{ $entry->postDate->diffForHumans() }})</li>
        @endforeach
    </ul>

    {{ $this->entries->links(data: ['scrollTo' => false]) }}
</div>
