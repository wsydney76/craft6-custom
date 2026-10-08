<?php

use Livewire\Livewire;

it('renders the contact page route', function (): void {
    $this->get(route('contact'))
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
