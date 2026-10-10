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
        $articleOfTheDayId = Cache::remember(
            'article_of_the_day_id',
            now()->addDay()->startOfDay(),
            fn () => Entry::find()->section('article')->inRandomOrder()->first()?->id
        );

        $articleOfTheDay = $articleOfTheDayId
            ? Entry::find()->id($articleOfTheDayId)->one()
            : null;

        return view('entries.home.show', compact('entry', 'articleOfTheDay'));
    }
}
