<?php

use App\View\Composers\ArticleOfTheDayComposer;
use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View as IlluminateView;

const ARTICLE_OF_THE_DAY_CACHE_KEY = 'article_of_the_day_id';

beforeEach(function (): void {
    Cache::forget(ARTICLE_OF_THE_DAY_CACHE_KEY);
});

afterEach(function (): void {
    Cache::forget(ARTICLE_OF_THE_DAY_CACHE_KEY);
});

function composeArticleOfTheDay(): ?Entry
{
    $capturedArticle = null;

    $view = Mockery::mock(IlluminateView::class);
    $view
        ->shouldReceive('with')
        ->once()
        ->withArgs(function (string $key, mixed $value) use (&$capturedArticle): bool {
            if ($key !== 'articleOfTheDay') {
                return false;
            }

            $capturedArticle = $value;

            return true;
        })
        ->andReturnSelf();

    (new ArticleOfTheDayComposer())->compose($view);

    return $capturedArticle;
}

it('keeps the same article id over multiple calls', function (): void {
    expect(Entry::find()->section('article')->first())->not->toBeNull();

    $firstArticle = composeArticleOfTheDay();
    $firstCachedId = Cache::get(ARTICLE_OF_THE_DAY_CACHE_KEY);

    $secondArticle = composeArticleOfTheDay();
    $secondCachedId = Cache::get(ARTICLE_OF_THE_DAY_CACHE_KEY);

    expect($firstArticle)->not->toBeNull();
    expect($secondArticle)->not->toBeNull();
    expect($firstCachedId)->toBeInt();
    expect($secondCachedId)->toBeInt();
    expect($secondCachedId)->toBe($firstCachedId);
    expect($secondArticle?->id)->toBe($firstArticle?->id);
});

it('sets and uses a valid article id when cache is empty', function (): void {
    expect(Entry::find()->section('article')->first())->not->toBeNull();
    expect(Cache::get(ARTICLE_OF_THE_DAY_CACHE_KEY))->toBeNull();

    $article = composeArticleOfTheDay();
    $cachedId = Cache::get(ARTICLE_OF_THE_DAY_CACHE_KEY);

    expect($article)->not->toBeNull();
    expect($cachedId)->toBeInt();
    expect($article?->id)->toBe($cachedId);
});

it('recovers when cache contains an invalid article id', function (): void {
    $validArticleIds = collect(Entry::find()->section('article')->all())
        ->pluck('id')
        ->all();

    expect($validArticleIds)->not->toBeEmpty();

    $invalidId = max($validArticleIds) + 1_000_000;
    Cache::put(ARTICLE_OF_THE_DAY_CACHE_KEY, $invalidId, now()->endOfDay());

    $article = composeArticleOfTheDay();
    $cachedIdAfterCompose = Cache::get(ARTICLE_OF_THE_DAY_CACHE_KEY);

    expect($article)->not->toBeNull();
    expect($cachedIdAfterCompose)->toBeInt();
    expect($cachedIdAfterCompose)->not->toBe($invalidId);
    expect($validArticleIds)->toContain($cachedIdAfterCompose);
    expect($article?->id)->toBe($cachedIdAfterCompose);
});
