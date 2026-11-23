<?php

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

use function CraftCms\Cms\t;

new class extends Component {
    use WithPagination;

    public string $show = 'unread'; // Options: all, unread, read

    public function render(): View
    {
        return $this->view()->title(t('Notifications', category: 'site'));
    }

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
    <flux:radio.group wire:model.live="show" :label="t('Select notifications', category: 'site')" variant="segmented">
        <flux:radio icon="inbox" value="all" :label="t('All', category: 'site')" />
        <flux:radio icon="envelope" value="unread" :label="t('Unread', category: 'site')" />
        <flux:radio icon="envelope-open" value="read" :label="t('Read', category: 'site')" />
    </flux:radio.group>

    @forelse ($this->notifications as $notification)
        <flux:card
            wire:key="notification-{{ $notification->id }}"
            class="flex justify-between gap-4"
        >
            <div>
                {{ t('Date:', category: 'site') }} {{ $notification->created_at->isoFormat('lll') }}
                <br />
                {{ t('Name:', category: 'site') }} {{ $notification->data['name'] }}
                <br />
                {{ t('Email:', category: 'site') }} {{ $notification->data['email'] }}
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
                        {{ t('Mark as unread', category: 'site') }}
                    </flux:button>
                @else
                    <flux:button
                        wire:click="markAsRead('{{ $notification->id }}')"
                        variant="outline"
                        color="green"
                        icon="envelope-open"
                        size="xs"
                    >
                        {{ t('Mark as read', category: 'site') }}
                    </flux:button>
                @endif

                <flux:button
                    wire:click="delete('{{ $notification->id }}')"
                    variant="outline"
                    color="red"
                    icon="trash"
                    size="xs"
                    wire:confirm="{{ t('Are you sure you want to delete this notification?', category: 'site') }}"
                >
                    {{ t('Delete', category: 'site') }}
                </flux:button>
            </div>
        </flux:card>
    @empty
        <p>{{ t('No notifications found.', category: 'site') }}</p>
    @endforelse

    {{ $this->notifications->links() }}
</div>
