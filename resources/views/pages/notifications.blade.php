<?php

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Notifications')] class extends Component {
    use WithPagination;

    public string $show = 'unread'; // Options: all, unread, read

    #[Computed]
    public function notifications()
    {
        $notifications = auth()
            ->user()
            ->notifications()
            ->where('type', 'contact-notification')
            ->latest();

        if ($this->show === 'unread') {
            $notifications->unread();
        }

        if ($this->show === 'read') {
            $notifications->read();
        }

        return $notifications->paginate(12);
    }

    public function markAsRead(DatabaseNotification $notification): void
    {
        $this->guard($notification);
        $notification->markAsRead();
    }

    public function markAsUnRead(DatabaseNotification $notification): void
    {
        $this->guard($notification);
        $notification->markAsUnRead();
    }

    public function delete(DatabaseNotification $notification): void
    {
        $this->guard($notification);
        $notification->delete();
    }

    private function guard(DatabaseNotification $notification): void
    {
        abort_unless(auth()->user()->id === $notification->notifiable_id, 403);
    }

    public function updatedShow(): void
    {
        $this->resetPage();
    }
};
?>

<div class="space-y-4">
    <flux:radio.group wire:model.live="show" label="Select notifications" variant="segmented">
        <flux:radio icon="inbox" value="all" label="All" />
        <flux:radio icon="envelope" value="unread" label="Unread" />
        <flux:radio icon="envelope-open" value="read" label="Read" />
    </flux:radio.group>

    @forelse ($this->notifications as $notification)
        <flux:card
            wire:key="notification-{{ $notification->id }}"
            class="flex justify-between gap-4"
        >
            <div>
                Date: {{ $notification->created_at->isoFormat('lll') }}
                <br />
                Name: {{ $notification->data['name'] }}
                <br />
                Email: {{ $notification->data['email'] }}
                <br /><br>
                <x-nl2br :text="$notification->data['message']" />
            </div>

            <div class="flex flex-col gap-2">
                @if ($notification->read())
                    <flux:button
                        wire:click="markAsUnRead('{{ $notification->id }}')"
                        variant="outline"
                        color="blue"
                        icon="envelope"
                        size="xs"
                    >
                        Mark as unread
                    </flux:button>
                @else
                    <flux:button
                        wire:click="markAsRead('{{ $notification->id }}')"
                        variant="outline"
                        color="green"
                        icon="envelope-open"
                        size="xs"
                    >
                        Mark as read
                    </flux:button>
                @endif

                <flux:button
                    wire:click="delete('{{ $notification->id }}')"
                    variant="outline"
                    color="red"
                    icon="trash"
                    size="xs"
                    wire:confirm="Are you sure you want to delete this notification?"
                >
                    Delete
                </flux:button>
            </div>
        </flux:card>
    @empty
        <p>No notifications found.</p>
    @endforelse

    {{ $this->notifications->links() }}
</div>
