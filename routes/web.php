<?php

// Search single section
Route::livewire('/search', 'pages::search')->name('search');

// Dynamically load test templates based on the template name passed in the URL
Route::get('tests/{template}', function (string $template) {
    return view("tests.{$template}", ['template' => $template]);
})
    ->middleware('auth')
    ->name('tests.switch');

// Test error pages
Route::get('errors/{template}', function (string $template) {
    return view("errors.{$template}");
})
    ->middleware('auth')
    ->name('404');
