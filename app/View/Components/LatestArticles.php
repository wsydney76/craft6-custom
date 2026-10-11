<?php

namespace App\View\Components;

use Closure;
use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LatestArticles extends Component
{
    public Collection $entries;

    public function __construct(?Entry $exclude = null, public string $heading, int $limit = 5)
    {
        $this->entries = Entry::find()
            ->section('article')
            ->when($exclude, fn($q) => $q->id(['not', $exclude->id]))
            ->latest('postDate')
            ->take($limit)
            ->get();
    }

    public function render(): View
    {
        return view('components.partials.entries-list');
    }
}
