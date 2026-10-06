@props([
    'nav',
])

<flux:header class="mb-8 flex justify-between bg-inherit dark:bg-inherit">
    <div class="flex items-center gap-4">
        <flux:brand :name="$nav['brand']['label']" {{ $attributes }}>
            <x-slot name="logo">
                <flux:icon icon="bolt" />
            </x-slot>
        </flux:brand>

        <flux:navbar class="max-md:hidden">
            @foreach ($nav['links'] as $link)
                <flux:navbar.item :current="$link['current']" href="{{ $link['url'] }}">
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
    @foreach ($nav['links'] as $link)
        <flux:sidebar.item :current="$link['current']" href="{{ $link['url'] }}">
            {{ $link['label'] }}
        </flux:sidebar.item>
    @endforeach
</flux:sidebar>
