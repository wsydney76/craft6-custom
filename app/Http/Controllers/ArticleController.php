<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Http\Request;

class ArticleController
{
    public function index(Entry $entry)
    {
        $articles = Entry::find()->section('article')->latest('postDate')->paginate(12);

        return view('entries.article.index', compact('entry', 'articles'));
    }

    public function show(Entry $entry)
    {
        $prev = $entry->getPrev(['section' => 'article']);
        $next = $entry->getNext(['section' => 'article']);

        return view('entries.article.show', compact('entry', 'prev', 'next'));
    }
}
