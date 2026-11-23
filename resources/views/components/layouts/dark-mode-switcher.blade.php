<flux:dropdown x-data align="end" {{ $attributes }}>
    <flux:button variant="subtle" square class="group" :aria-label="\CraftCms\Cms\t('Preferred color scheme', category: 'site')">
        <flux:icon.sun
            x-show="$flux.appearance === 'light'"
            variant="mini"
            class="text-zinc-500 dark:text-white"
        />
        <flux:icon.moon
            x-show="$flux.appearance === 'dark'"
            variant="mini"
            class="text-zinc-500 dark:text-white"
        />
        <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
        <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
    </flux:button>

    <flux:menu>
        <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">{{ \CraftCms\Cms\t('Light', category: 'site') }}</flux:menu.item>
        <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">{{ \CraftCms\Cms\t('Dark', category: 'site') }}</flux:menu.item>
        <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">
            {{ \CraftCms\Cms\t('System', category: 'site') }}
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
