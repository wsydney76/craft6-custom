<?php

use App\Data\ContactDTO;
use App\Notifications\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ContactDTO::make(
            name: 'Sabine Mustermann',
            email: 'sabine.mustermann@example.com',
            message: fake()->paragraphs(3, true),
        ),
    )->toMail(auth()->user());
})
    ->middleware('auth')
    ->name('preview.notification');

Route::get('/login', function () {
    return response()->redirectTo(request()->get('redirect'));
})
    ->middleware('auth')
    ->name('login');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->redirectTo(url('/'));
})
    ->middleware('auth')
    ->name('logout');
