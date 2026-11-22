<?php

namespace App\Data;

class NavigationData
{
    public static function getNavItems(): array
    {
        $nav = [
            'brand' => [
                'label' => config('app.name'),
                'url' => url('/'),
                'logo' => 'bolt',
            ],
            'links' => [
                [
                    'label' => 'Articles',
                    'url' => url('/articles'),
                    'current' => request()->is('articles') || request()->is('articles/*'),
                    'badge' => null,
                ],
                [
                    'label' => 'Search',
                    'url' => url('/search'),
                    'current' => request()->is('search'),
                    'badge' => null,
                ],
                [
                    'label' => 'Contact',
                    'url' => url('/contact'),
                    'current' => request()->is('contact'),
                    'badge' => null,
                ],
            ],
        ];

        return $nav;
    }
}
