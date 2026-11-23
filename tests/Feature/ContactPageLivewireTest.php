<?php

use CraftCms\Cms\View\TemplateMode;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

it('renders the contact form on the Craft contact page', function (): void {
    TemplateMode::set(TemplateMode::Site);

    $this->get('/contact')
        ->assertOk()
        ->assertSee('Your name')
        ->assertSee('Your E-Mail')
        ->assertSee('Your message');
});

it('starts in empty state with the form visible', function (): void {
    Livewire::test('pages::contact')
        ->assertSet('state', 'empty')
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('message', '')
        ->assertSee('Send message')
        ->assertSee('Reset');
});

it('validates required and malformed input', function (): void {
    Livewire::test('pages::contact')
        ->set('name', '')
        ->set('email', 'not-an-email')
        ->set('message', 'short')
        ->call('send')
        ->assertHasErrors([
            'name' => ['required'],
            'email' => ['email'],
            'message' => ['min'],
        ])
        ->assertSet('state', 'empty');
});

it('shows German validation messages when the locale is German', function (): void {
    app()->setLocale('de');
    $component = Livewire::test('pages::contact');

    $component
        ->set('email', 'not-an-email')
        ->call('send')
        ->assertSee('„Ihr Name“ ist ein Pflichtfeld.')
        ->assertSee('Geben Sie für „Ihre E-Mail-Adresse“ eine gültige E-Mail-Adresse ein.')
        ->assertSee('Bitte geben Sie eine Nachricht mit mindestens 10 Zeichen ein.');
});

it('normalizes values before attempting to send', function (): void {
    Livewire::test('pages::contact')
        ->set('name', '  Jane Doe  ')
        ->set('email', '  JANE.DOE@EXAMPLE.COM  ')
        ->set('message', '  This is a sufficiently long message.  ')
        ->call('normalizeValues')
        ->assertSet('name', 'Jane Doe')
        ->assertSet('email', 'jane.doe@example.com')
        ->assertSet('message', 'This is a sufficiently long message.');
});

it('handles successful send attempts with the expected UI states', function (): void {
    $initialNotificationCount = DB::table('notifications')->count();

    DB::beginTransaction();

    try {
        Livewire::test('pages::contact')
            ->set('name', 'Jane')
            ->set('email', 'jane@example.com')
            ->set('state', 'success')
            ->set('message', 'This message is definitely long enough.')
            ->call('send')
            ->assertSet('state', 'success')
            ->assertSet('message', '')
            ->assertSee('Thank you, Jane!')
            ->assertDontSee('Send message')
            ->assertSet('state', 'success');
    } finally {
        DB::rollBack();
    }

    expect(DB::table('notifications')->count())->toBe($initialNotificationCount);
});

it('clears values, validation errors, and state when reset is clicked', function (): void {
    Livewire::test('pages::contact')
        ->set('name', '')
        ->set('email', 'bad-email')
        ->set('message', 'tiny')
        ->call('send')
        ->assertHasErrors()
        ->set('state', 'error')
        ->call('clear')
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('message', '')
        ->assertSet('state', 'empty')
        ->assertHasNoErrors();
});
