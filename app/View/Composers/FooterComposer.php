<?php

namespace App\View\Composers;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\MatchedElement;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FooterComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $copyright = '© ' . date('Y') . ' ' . config('app.name');

        $view->with('copyright', $copyright)->with('currentElement', MatchedElement::get());
    }
}
