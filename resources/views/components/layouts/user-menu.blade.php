<flux:dropdown x-data align="end" {{ $attributes }}>
    <flux:button
        variant="subtle"
        square
        class="group"
        tooltip="User menu"
    >
        @guest
            <flux:icon.user variant="mini" />
        @endguest

        @auth
            <flux:avatar size="xs" circle color="auto" name="{{ $currentUser->fullName }}" />
        @endauth
    </flux:button>

    <flux:menu>
        @auth
            <div class="flex items-center gap-2 px-2 py-2 text-xs text-zinc-500 dark:text-zinc-200">
                <flux:avatar size="xs" circle color="gray" name="{{ $currentUser->fullName }}" />
                {{ $currentUser->fullName }}
            </div>

            @can('accessCp')
                <flux:menu.separator />
                <flux:menu.item :href="route('notifications')" icon="paper-airplane">
                    Notifications
                </flux:menu.item>
                <flux:menu.item :href="route('craft.cp.')" icon="cog">
                    Craft Control Panel
                </flux:menu.item>
                {{-- <flux:menu.item href="/filament" icon="cog">Filament Panel</flux:menu.item> --}}
            @endcan

            <flux:menu.separator />
        @endauth

        <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
        <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
        <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">
            System
        </flux:menu.item>
        <flux:menu.separator />
        @auth
            <flux:menu.item :href="route('logout')" icon="arrow-left-start-on-rectangle">
                Logout
            </flux:menu.item>
        @endauth

        @guest
            <flux:menu.item
                href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                icon="arrow-left-end-on-rectangle"
            >
                Login
            </flux:menu.item>
        @endguest
    </flux:menu>
</flux:dropdown>
