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
                'url' => route('home'),
                'logo' => 'bolt',
            ],
            'links' => [
                [
                    'label' => 'Articles',
                    'url' => route('articles.index'),
                    'current' => request()->is('articles') || request()->is('articles/*'),
                ],
                [
                    'label' => 'Search',
                    'url' => route('search'),
                    'current' => request()->is('search'),
                ],
                [
                    'label' => 'Contact',
                    'url' => route('contact'),
                    'current' => request()->is('contact'),
                ],
            ],
        ];

        if (auth()->check()) {
            $nav['links'][] = [
                'label' => 'Notifications',
                'url' => route('notifications'),
                'current' => request()->is('notifications'),
            ];
        }

        $view->with('nav', $nav);
    }
}
