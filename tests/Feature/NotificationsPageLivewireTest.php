<?php

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->initialNotificationsCount = DB::table('notifications')->count();
    DB::beginTransaction();
});

afterEach(function (): void {
    DB::rollBack();

    expect(DB::table('notifications')->count())->toBe($this->initialNotificationsCount);
});

function createContactNotification(User $user, array $attributes = []): DatabaseNotification
{
    return $user->notifications()->create(
        array_merge(
            [
                'id' => (string) Str::uuid(),
                'type' => 'contact-notification',
                'data' => [
                    'name' => 'Default Sender',
                    'email' => 'sender@example.com',
                    'message' => 'Default message body.',
                ],
                'read_at' => null,
            ],
            $attributes,
        ),
    );
}

function testUser(): User
{
    return User::query()->firstOrFail();
}

it('requires authentication for the notifications route', function (): void {
    $this->get(route('notifications'))->assertRedirect();
});

it('renders notifications page for an authenticated user', function (): void {
    $user = testUser();

    $this->actingAs($user)
        ->get(route('notifications'))
        ->assertOk()
        ->assertSee('Select notifications');
});

/*it('shows an empty state when there are no contact notifications', function (): void {
    $user = testUser();

    Livewire::actingAs($user)
        ->test('pages::notifications')
        ->assertSet('show', 'unread')
        ->assertSee('No notifications found.');
});*/

it('shows only contact notifications in newest-first order', function (): void {
    $user = testUser();

    createContactNotification($user, [
        'data' => [
            'name' => 'Older Sender',
            'email' => 'older@example.com',
            'message' => 'Older message body.',
        ],
        'created_at' => now()->subHour(),
        'updated_at' => now()->subHour(),
    ]);

    createContactNotification($user, [
        'data' => [
            'name' => 'Newer Sender',
            'email' => 'newer@example.com',
            'message' => 'Newest message body.',
        ],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'non-contact-notification',
        'data' => [
            'name' => 'Should Not Render',
            'email' => 'hidden@example.com',
            'message' => 'This notification should be filtered out.',
        ],
        'read_at' => null,
        'created_at' => now()->addMinute(),
        'updated_at' => now()->addMinute(),
    ]);

    Livewire::actingAs($user)
        ->test('pages::notifications')
        ->assertSee('Newer Sender')
        ->assertSee('newer@example.com')
        ->assertSee('Newest message body.')
        ->assertSee('Older Sender')
        ->assertDontSee('Should Not Render')
        ->assertSeeInOrder(['Newer Sender', 'Older Sender']);
});

it('filters between unread, read, and all notification states', function (): void {
    $user = testUser();

    createContactNotification($user, [
        'data' => [
            'name' => 'Unread Sender',
            'email' => 'unread@example.com',
            'message' => 'Unread body.',
        ],
        'read_at' => null,
    ]);

    createContactNotification($user, [
        'data' => [
            'name' => 'Read Sender',
            'email' => 'read@example.com',
            'message' => 'Read body.',
        ],
        'read_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test('pages::notifications')
        ->assertSee('Unread Sender')
        ->assertDontSee('Read Sender')
        ->set('show', 'read')
        ->assertSee('Read Sender')
        ->assertDontSee('Unread Sender')
        ->set('show', 'All')
        ->assertSee('Unread Sender')
        ->assertSee('Read Sender');
});

it('marks an unread notification as read', function (): void {
    $user = testUser();
    $notification = createContactNotification($user, ['read_at' => null]);

    expect($notification->fresh()->read_at)->toBeNull();

    Livewire::actingAs($user)->test('pages::notifications')->call('markAsRead', $notification->id);

    expect($notification->fresh()->read_at)
        ->not()
        ->toBeNull();
});

it('marks a read notification as unread', function (): void {
    $user = testUser();
    $notification = createContactNotification($user, ['read_at' => now()]);

    expect($notification->fresh()->read_at)
        ->not()
        ->toBeNull();

    Livewire::actingAs($user)
        ->test('pages::notifications')
        ->call('markAsUnRead', $notification->id);

    expect($notification->fresh()->read_at)->toBeNull();
});

it('deletes a notification owned by the authenticated user', function (): void {
    $user = testUser();
    $notification = createContactNotification($user);

    expect(DatabaseNotification::query()->whereKey($notification->id)->exists())->toBeTrue();

    Livewire::actingAs($user)->test('pages::notifications')->call('delete', $notification->id);

    expect(DatabaseNotification::query()->whereKey($notification->id)->exists())->toBeFalse();
});

it('forbids mutating notifications owned by another user', function (): void {
    $actor = testUser();
    $foreignNotifiableId = is_numeric((string) $actor->id)
        ? (string) (((int) $actor->id) + 999999)
        : (string) $actor->id . '-foreign';

    $foreignNotification = DatabaseNotification::query()->create([
        'id' => (string) Str::uuid(),
        'type' => 'contact-notification',
        'notifiable_type' => User::class,
        'notifiable_id' => $foreignNotifiableId,
        'data' => [
            'name' => 'Foreign Sender',
            'email' => 'foreign@example.com',
            'message' => 'Foreign message body.',
        ],
        'read_at' => null,
    ]);

    Livewire::actingAs($actor)
        ->test('pages::notifications')
        ->call('markAsRead', $foreignNotification->id)
        ->assertForbidden();

    expect($foreignNotification->fresh()->read_at)->toBeNull();
});
