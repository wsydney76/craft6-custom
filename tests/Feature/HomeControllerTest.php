<?php

use App\Http\Controllers\HomeController;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\Elements;
use CraftCms\Cms\Support\Facades\EntryTypes;
use CraftCms\Cms\Support\Facades\Sections;
use CraftCms\Cms\User\Elements\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

beforeEach(function (): void {
    DB::beginTransaction();
    Cache::forget('article_of_the_day_id');
});

afterEach(function (): void {
    Cache::forget('article_of_the_day_id');
    DB::rollBack();
});

function createArticleEntryForHomeTest(string $title): Entry
{
    $section = Sections::getSectionByHandle('article');
    $type = EntryTypes::getEntryTypeByHandle('article');
    $author = User::find()->admin()->one() ?? User::find()->one();

    expect($section)->not->toBeNull();
    expect($type)->not->toBeNull();
    expect($author)->not->toBeNull();

    $entry = new Entry();
    $entry->sectionId = $section->id;
    $entry->typeId = $type->id;
    $entry->authorId = $author->id;
    $entry->title = $title;
    $entry->slug = Str::slug($title) . '-' . Str::lower(Str::random(6));
    $entry->postDate = now();

    expect(Elements::saveElement($entry))->toBeTrue();

    return $entry;
}

it('keeps article of the day stable across repeated show calls', function (): void {
    createArticleEntryForHomeTest('First Home Controller Test Article');
    createArticleEntryForHomeTest('Second Home Controller Test Article');

    $controller = new HomeController();
    $homeEntry = new Entry();

    $firstView = $controller->show($homeEntry);
    $secondView = $controller->show($homeEntry);

    /** @var Entry|null $firstArticleOfTheDay */
    $firstArticleOfTheDay = $firstView->getData()['articleOfTheDay'] ?? null;
    /** @var Entry|null $secondArticleOfTheDay */
    $secondArticleOfTheDay = $secondView->getData()['articleOfTheDay'] ?? null;

    expect($firstArticleOfTheDay)->not->toBeNull();
    expect($secondArticleOfTheDay)->not->toBeNull();
    expect($secondArticleOfTheDay->id)->toBe($firstArticleOfTheDay->id);
    expect(Cache::get('article_of_the_day_id'))->toBe($firstArticleOfTheDay->id);
});
