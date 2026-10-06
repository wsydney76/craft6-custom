<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Http\Request;

class ArticleController
{
    public function index(Entry $entry)
    {
        $articles = Entry::find()->section('article')->latest('postDate')->paginate(12);

        return view('_entries.articles.index', compact('entry', 'articles'));
    }
}
