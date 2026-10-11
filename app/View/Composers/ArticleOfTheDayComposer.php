<?php

namespace App\View\Composers;
use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ArticleOfTheDayComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $cacheExpiresAt = now()->endOfDay();

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

        $view->with('articleOfTheDay', $articleOfTheDay);
    }
}
