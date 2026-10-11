<?php

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\Elements;
use CraftCms\Cms\Support\Facades\EntryTypes;
use CraftCms\Cms\Support\Facades\Sections;
use CraftCms\Cms\User\Elements\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

beforeEach(function (): void {
    DB::beginTransaction();
});

afterEach(function (): void {
    DB::rollBack();
});

function createArticleWithTeaser(string $title, string $teaser): Entry
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
    $entry->setFieldValue('teaser', $teaser);

    expect(Elements::saveElement($entry))->toBeTrue();

    return $entry;
}

it('renders html when no format is set', function (): void {
    $article = createArticleWithTeaser(
        'Article Show Default Format Title',
        'Article Show Default Format Teaser',
    );

    $response = $this->get($article->url);

    $response->assertOk()->assertSeeText($article->title)->assertSeeText($article->teaser);
    expect($response->headers->get('content-type'))->toStartWith('text/html');
});

it('renders html when format=html', function (): void {
    $article = createArticleWithTeaser(
        'Article Show HTML Format Title',
        'Article Show HTML Format Teaser',
    );

    $response = $this->get($article->url . '?format=html');

    $response->assertOk()->assertSeeText($article->title)->assertSeeText($article->teaser);
    expect($response->headers->get('content-type'))->toStartWith('text/html');
});

it('renders html when format=twig', function (): void {
    $article = createArticleWithTeaser(
        'Article Show Twig Format Title',
        'Article Show Twig Format Teaser',
    );

    $response = $this->get($article->url . '?format=twig');

    $response->assertOk()->assertSeeText($article->title)->assertSeeText($article->teaser);
    expect($response->headers->get('content-type'))->toStartWith('text/html');
});

it('renders json when format=json', function (): void {
    $article = createArticleWithTeaser(
        'Article Show JSON Format Title',
        'Article Show JSON Format Teaser',
    );

    $response = $this->get($article->url . '?format=json');

    $response
        ->assertOk()
        ->assertJsonPath('title', $article->title)
        ->assertJsonPath('teaser', $article->teaser);
    expect($response->headers->get('content-type'))->toStartWith('application/json');
});

it('renders pdf when format=pdf', function (): void {
    $article = createArticleWithTeaser(
        'Article Show PDF Format Title',
        'Article Show PDF Format Teaser',
    );

    $response = $this->get($article->url . '?format=pdf');

    $response->assertOk();
    expect($response->headers->get('content-type'))->toStartWith('application/pdf');
    expect($response->getContent())->toStartWith('%PDF');
});

it('throws an error when format is invalid', function (): void {
    $article = createArticleWithTeaser(
        'Article Show Invalid Format Title',
        'Article Show Invalid Format Teaser',
    );

    $this->withoutExceptionHandling();

    expect(fn() => $this->get($article->url . '?format=invalid'))->toThrow(
        InvalidArgumentException::class,
    );
});
