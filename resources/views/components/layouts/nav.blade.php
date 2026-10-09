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

    #[On('notifications-updated')]
    public function notificationsUpdated(): void
    {
        if (! auth()->check()) {
            return;
        }

        foreach ($this->nav['links'] as &$link) {
            if (isset($link['key']) && $link['key'] === 'notifications') {
                $link['badge'] = auth()
                    ->user()
                    ->unreadNotifications()
                    ->count();
                break;
            }
        }
    }
};

?>

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
