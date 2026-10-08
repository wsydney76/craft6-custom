<?php

// Search single section
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'show'])->name('home');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::livewire('/search', 'pages::search')->name('search');
Route::livewire('/contact', 'pages::contact')->name('contact');

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
