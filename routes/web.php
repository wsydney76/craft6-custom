<?php

Route::livewire('/search', 'pages::search')->name('search');

Route::get('tests/{template}', function (string $template) {
    return view("tests.{$template}", ['template' => $template]);
})->name('tests.switch');
