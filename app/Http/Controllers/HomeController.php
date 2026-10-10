<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController
{
    public function show(#[CurrentElement] Entry $entry)
    {
        $cacheExpiresAt = now()->addDay()->startOfDay();

        $articleOfTheDayId = Cache::remember(
            'article_of_the_day_id',
            $cacheExpiresAt,
            fn() => Entry::find()->section('article')->inRandomOrder()->first()?->id,
        );

        $articleOfTheDay = Entry::findOne($articleOfTheDayId);

        if ($articleOfTheDayId && !$articleOfTheDay) {
            $articleOfTheDay = Entry::find()->section('article')->inRandomOrder()->first();
            Cache::put('article_of_the_day_id', $articleOfTheDay?->id, $cacheExpiresAt);
        }

        return view('entries.home.show', compact('entry', 'articleOfTheDay'));
    }
}
