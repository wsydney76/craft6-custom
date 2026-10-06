<?php

namespace App\View\Composers;

use Illuminate\View\View;

class NavComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // TODO: Make this dynamic
        $nav = [
            'brand' => [
                'label' => config('app.name'),
                'url' => '/',
                'logo' => 'bolt',
            ],
            'links' => [
                [
                    'label' => 'Articles',
                    'url' => '/articles',
                    'current' => request()->is('articles') || request()->is('articles/*'),
                ],
                [
                    'label' => 'Search',
                    'url' => '/search',
                    'current' => request()->is('search'),
                ],
            ],
        ];

        $view->with('nav', $nav);
    }
}
