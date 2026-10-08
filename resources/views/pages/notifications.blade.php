<?php

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public bool $showAll = false;

    public function render()
    {
        return $this->view()->title('Notifications');
    }

    #[Computed]
    public function notifications()
    {
        $notifications = auth()
            ->user()
            ->notifications()
            ->where('type', 'contact-notification')
            ->latest();

        if (! $this->showAll) {
            $notifications->whereNull('read_at');
        }

        return $notifications->get();
    }

    public function toggleNotifications(): void
    {
        $this->showAll = ! $this->showAll;
    }

    public function markAsRead(DatabaseNotification $notification): void
    {
        $notification->markAsRead();
    }
};
?>

@craftRequireLogin

<div class="space-y-4">
    <flux:button wire:click="toggleNotifications" variant="filled">
        {{ $showAll ? 'Show unread notifications' : 'Show all notifications' }}
    </flux:button>

    @forelse ($this->notifications as $notification)
        <flux:card class="flex justify-between gap-4 space-y-4">
            <div>

                Date: {{ $notification->created_at->format('Y-m-d H:i') }}
                <br />
                Name: {{ $notification->data['name'] }}
                <br />
                Email: {{ $notification->data['email'] }}
                <br />
                Message:
                <br />
                <x-nl2br :text="$notification->data['message']" />
            </div>

            <div class="">
                @if ($notification->read_at)
                    <span>Read</span>
                @else
                    <flux:button
                        wire:click="markAsRead('{{ $notification->id }}')"
                        variant="filled"
                        size="xs"
                    >
                        Mark as read
                    </flux:button>
                @endif
            </div>
        </flux:card>
    @empty
        <p>{{ $showAll ? 'No notifications.' : 'No unread notifications.' }}</p>
    @endforelse
</div>
