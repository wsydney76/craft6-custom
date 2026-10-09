<?php

namespace App\Data;

class NavigationData
{
    public static function getNavItems(): array
    {
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
                    'badge' => null,
                ],
                [
                    'label' => 'Search',
                    'url' => route('search'),
                    'current' => request()->is('search'),
                    'badge' => null,
                ],
                [
                    'label' => 'Contact',
                    'url' => route('contact'),
                    'current' => request()->is('contact'),
                    'badge' => null,
                ],
            ],
        ];

        if (auth()->check()) {
            $nav['links'][] = [
                'key' => 'notifications',
                'label' => 'Notifications',
                'url' => route('notifications'),
                'current' => request()->is('notifications'),
                'badge' => auth()->user()->unreadNotifications->count(),
            ];
        }

        return $nav;
    }
}
