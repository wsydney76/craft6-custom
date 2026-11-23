@props([]) {{-- We need an empty props here, so that a call from Twig via blade() does not fail  --}}

<?php

use App\Data\NavigationData;

$nav = NavigationData::getNavItems();
?>

<div>
    <flux:header
        class="mb-8 flex justify-between border-b border-gray-500 bg-inherit dark:bg-inherit"
    >
        <div class="flex items-center gap-4">
            <flux:brand :name="$nav['brand']['label']" {{ $attributes }}>
                <x-slot name="logo">
                    <flux:icon :icon="$nav['brand']['logo']" />
                </x-slot>
            </flux:brand>

            <flux:navbar class="max-md:hidden">
                @foreach ($nav['links'] as $link)
                    <flux:navbar.item
                        :id="$link['key'] ?? null"
                        :badge="$link['badge']"
                        :current="$link['current']"
                        href="{{ $link['url'] }}"
                    >
                        {{ \CraftCms\Cms\t($link['label'], category: 'site') }}
                    </flux:navbar.item>
                @endforeach
            </flux:navbar>
        </div>

        <div class="flex items-center gap-2">
            <div id="navslot"></div>
            <flux:sidebar.toggle class="md:hidden" icon="bars-3" inset="left" />
            {{-- <x-layouts.dark-mode-switcher /> --}}
            <x-layouts.user-menu />
        </div>
    </flux:header>

    <flux:sidebar
        sticky
        collapsible="mobile"
        breakpoint="768"
        class="border-r border-zinc-200 bg-zinc-50 md:hidden dark:border-zinc-700 dark:bg-zinc-900"
    >
        @foreach ($nav['links'] as $link)
            <flux:sidebar.item
                :badge="$link['badge']"
                :current="$link['current']"
                href="{{ $link['url'] }}"
            >
                {{ \CraftCms\Cms\t($link['label'], category: 'site') }}
            </flux:sidebar.item>
        @endforeach
    </flux:sidebar>
</div>
