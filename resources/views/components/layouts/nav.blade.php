<?php

use App\Data\NavigationData;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public array $nav = [];

    public function mount()
    {
        $this->nav = NavigationData::getNavItems();
    }

    /**
     * @param int $unreadCount
     * @return void
     *
     * Updates the 'Notifications' unread count badge with the new unread count.
     *
     * Calling NavigationData::getNavItems() in an Ajax request would not detect the item as 'current'
     * because the request is not aware of the current route.
     * Therefore, we update the badge count manually.
     *
     * The 'notifications-updated' event is dispatched from the Notifications page when a notification is marked as read/unread or deleted,
     * which triggers an additional Ajax request to update the unread count badge in the navigation.
     *
     * To avoid that, we could update the badge via Alpine JS (see commented-out code below),
     * but that requires a DOM query depending on Flux's internal HTML structure, which is not ideal.
     *
     */

    #[On('notifications-updated')]
    public function notificationsUpdated(int $unreadCount): void
    {
        if (! auth()->check()) {
            return;
        }

        foreach ($this->nav['links'] as &$link) {
            if (isset($link['key']) && $link['key'] === 'notifications') {
                $link['badge'] = $unreadCount;
                break;
            }
        }
    }
};

?>

{{--
    <div
    x-data="{
    updateCount(unreadCount) {
    const elements = document.querySelectorAll('#notifications span')
    elements.forEach((element) => {
    element.textContent = unreadCount
    })
    },
    }"
    @notifications-updated.window="updateCount($event.detail.unreadCount)"
    >
--}}
<div>
    <flux:header class="mb-8 flex justify-between bg-inherit dark:bg-inherit">
        <div class="flex items-center gap-4">
            <flux:brand :name="$this->nav['brand']['label']" {{ $attributes }}>
                <x-slot name="logo">
                    <flux:icon :icon="$this->nav['brand']['logo']" />
                </x-slot>
            </flux:brand>

            <flux:navbar class="max-md:hidden">
                @foreach ($this->nav['links'] as $link)
                    <flux:navbar.item
                        :id="$link['key'] ?? null"
                        :badge="$link['badge']"
                        :current="$link['current']"
                        href="{{ $link['url'] }}"
                    >
                        {{ $link['label'] }}
                    </flux:navbar.item>
                @endforeach
            </flux:navbar>
        </div>

        <div>
            <flux:sidebar.toggle class="md:hidden" icon="bars-3" inset="left" />
            <x-layouts.dark-mode-switcher />
        </div>
    </flux:header>

    <flux:sidebar
        sticky
        collapsible="mobile"
        breakpoint="768"
        class="border-r border-zinc-200 bg-zinc-50 md:hidden dark:border-zinc-700 dark:bg-zinc-900"
    >
        @foreach ($this->nav['links'] as $link)
            <flux:sidebar.item
                :badge="$link['badge']"
                :current="$link['current']"
                href="{{ $link['url'] }}"
            >
                {{ $link['label'] }}
            </flux:sidebar.item>
        @endforeach
    </flux:sidebar>
</div>
