<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use Illuminate\Http\Request;

class ArticleController
{
    public function index(#[CurrentElement] Entry $entry)
    {
        $articles = Entry::find()->section('article')->latest('postDate')->paginate(12);

        return view('entries.article.index', compact('entry', 'articles'));
    }

    public function show(#[CurrentElement] Entry $entry)
    {
        $prev = $entry->getPrev(['section' => 'article']);
        $next = $entry->getNext(['section' => 'article']);

        return view('entries.article.show', compact('entry', 'prev', 'next'));
    }
}
