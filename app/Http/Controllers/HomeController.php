<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;

class HomeController
{
    public function show(#[CurrentElement] Entry $entry)
    {
        return view('entries.home.show', compact('entry'));
    }
}
