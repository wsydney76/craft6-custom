<flux:dropdown x-data align="end" {{ $attributes }}>
    <flux:button
        variant="subtle"
        square
        class="group"
        :tooltip="\CraftCms\Cms\t('User menu', category: 'site')"
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
                    {{ \CraftCms\Cms\t('Notifications', category: 'site') }}
                </flux:menu.item>
                <flux:menu.item :href="route('craft.cp.')" icon="cog">
                    {{ \CraftCms\Cms\t('Craft Control Panel', category: 'site') }}
                </flux:menu.item>
                {{-- <flux:menu.item href="/filament" icon="cog">Filament Panel</flux:menu.item> --}}
            @endcan

            <flux:menu.separator />
        @endauth

        <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">{{ \CraftCms\Cms\t('Light', category: 'site') }}</flux:menu.item>
        <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">{{ \CraftCms\Cms\t('Dark', category: 'site') }}</flux:menu.item>
        <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">
            {{ \CraftCms\Cms\t('System', category: 'site') }}
        </flux:menu.item>
        <flux:menu.separator />
        @auth
            <flux:menu.item :href="route('logout')" icon="arrow-left-start-on-rectangle">
                {{ \CraftCms\Cms\t('Logout', category: 'site') }}
            </flux:menu.item>
        @endauth

        @guest
            <flux:menu.item
                href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                icon="arrow-left-end-on-rectangle"
            >
                {{ \CraftCms\Cms\t('Login', category: 'site') }}
            </flux:menu.item>
        @endguest
    </flux:menu>
</flux:dropdown>
