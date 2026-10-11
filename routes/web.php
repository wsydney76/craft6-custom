<?php

// Search single section
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Notifications\ContactNotification;

Route::get('/', [HomeController::class, 'show'])->name('home');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::livewire('/search', 'pages::search')->name('search');
Route::livewire('/contact', 'pages::contact')->name('contact');
Route::livewire('/notifications', 'pages::notifications')
    ->middleware('auth')
    ->name('notifications');

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

Route::get('/preview/notification', function () {
    return new ContactNotification(
        'Sabine Mustermann',
        'sabine.mustermann@example.com',
        fake()->paragraphs(3, true),
    )->toMail(auth()->user());
})
    ->middleware('auth')
    ->name('preview.notification');
