<?php

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\Elements;
use CraftCms\Cms\Support\Facades\EntryTypes;
use CraftCms\Cms\Support\Facades\Sections;
use CraftCms\Cms\User\Elements\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

beforeEach(function (): void {
    DB::beginTransaction();
});

afterEach(function (): void {
    DB::rollBack();
});

function createArticleEntry(string $title, DateTimeInterface $postDate): Entry
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
    $entry->postDate = $postDate;

    expect(Elements::saveElement($entry))->toBeTrue();

    return $entry;
}

it(
    'shows the newest article title in h1 but excludes it from the latest articles list',
    function (): void {
        $olderArticle = createArticleEntry('Older Article For Show Test', now()->subDay());
        $newestArticle = createArticleEntry('Newest Article For Show Test', now());

        $response = $this->get(
            route('articles.show', ['slug' => $newestArticle->slug]),
        )->assertOk();

        $html = $response->getContent();

        $crawler = new Crawler($html);
        $h1Text = trim($crawler->filter('main h1')->first()->text());

        expect($h1Text)->toBe($newestArticle->title);

        $afterLatestHeading = explode('More Latest Articles', $html, 2)[1] ?? '';
        expect($afterLatestHeading)->not->toBe('');

        preg_match('/<ul>(.*?)<\/ul>/s', $afterLatestHeading, $matches);
        $latestArticlesListHtml = $matches[1] ?? '';

        expect($latestArticlesListHtml)->toContain($olderArticle->title);
        expect($latestArticlesListHtml)->not->toContain($newestArticle->title);
    },
);
