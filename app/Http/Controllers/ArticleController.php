<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use function CraftCms\Cms\template;

class ArticleController
{
    public function index(#[CurrentElement] Entry $entry, string $format)
    {
        $articles = Entry::find()->section('article')->latest('postDate')->paginate(12);

        return match ($format) {
            'html' => view('entries.article.index', compact('entry', 'articles')),
            // This is just a demo, do not use this in production.
            // Use a proper resource or transformer to format the data.
            'json' => response()->json($articles, options: JSON_PRETTY_PRINT),
            'pdf' => Pdf::loadView('entries.article.index-pdf', [
                'entry' => $entry,
                'articles' => $articles,
            ])->stream('articles.pdf'),
            'twig' => template('entries/article/index-twig.twig', [
                'entry' => $entry,
                'articles' => $articles,
            ]),
            default => throw new \InvalidArgumentException("Invalid format [$format]."),
        };
    }

    public function show(#[CurrentElement] Entry $entry, string $slug, string $format)
    {
        return match ($format) {
            'html' => view('entries.article.show', [
                'entry' => $entry,
                'prev' => $entry->getPrev(['section' => 'article']),
                'next' => $entry->getNext(['section' => 'article']),
            ]),
            // This is just a demo, do not use this in production.
            // Use a proper resource or transformer to format the data.
            'json' => response()->json($entry, options: JSON_PRETTY_PRINT),
            'pdf' => Pdf::loadView('entries.article.show-pdf', ['entry' => $entry])->stream(
                "{$entry->slug}.pdf",
            ),
            'twig' => template('entries/article/show-twig.twig', ['entry' => $entry]),
            default => throw new \InvalidArgumentException("Invalid format [$format]."),
        };
    }
}
