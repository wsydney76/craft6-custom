<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use CraftCms\Cms\View\TemplateMode;
use function CraftCms\Cms\template;
use function request;

/**
 * Passing the format via route ->defaults('format', request('format', 'html')) does not work in pest tests,
 * so we get it from the query string instead
 * web.php is evaluated when routes are registered,
 * so it’s not a reliable in-request format switch.
 *
 * JSON format is just a demo spitting out Craft’s raw JSON representation of the entry.
 * PDF format is just a demo generating a PDF with unstyled HTML content of the entry.
 */

class ArticleController
{
    public function index(#[CurrentElement] Entry $entry)
    {
        $format = request()->query('format', 'html');

        $articles = Entry::find()->section('article')->latest('postDate')->paginate(12);

        return match ($format) {
            'html' => view('entries.article.index', compact('entry', 'articles')),
            'json' => response()->json($articles, options: JSON_PRETTY_PRINT),
            'pdf' => Pdf::loadView('entries.article.index-pdf', [
                'entry' => $entry,
                'articles' => $articles,
            ])->stream('articles.pdf'),
            'twig' => template(
                'entries/article/index-twig.twig',
                [
                    'entry' => $entry,
                    'articles' => $articles,
                ],
                templateMode: TemplateMode::Site,
            ),
            default => throw new \InvalidArgumentException("Invalid format [$format]."),
        };
    }

    public function show(#[CurrentElement] Entry $entry)
    {
        $format = request()->query('format', 'html');

        return match ($format) {
            'html' => view('entries.article.show', [
                'entry' => $entry,
                'prev' => $entry->getPrev(['section' => 'article']),
                'next' => $entry->getNext(['section' => 'article']),
            ]),
            'json' => response()->json($entry, options: JSON_PRETTY_PRINT),
            'pdf' => Pdf::loadView('entries.article.show-pdf', ['entry' => $entry])->stream(
                "{$entry->slug}.pdf",
            ),
            'twig' => template(
                'entries/article/show-twig.twig',
                ['entry' => $entry],
                templateMode: TemplateMode::Site, // required for pest tests
            ),
            default => throw new \InvalidArgumentException("Invalid format [$format]."),
        };
    }
}
