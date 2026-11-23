<x-layouts::app :title="\CraftCms\Cms\t('403 - Not Authorized', category: 'site')">
    <x-prose>
        <p>{{ \CraftCms\Cms\t('Sorry, you are not allowed to perform this action.', category: 'site') }}</p>
        <p><a href="{{ url('/') }}">{{ \CraftCms\Cms\t('Return to Home', category: 'site') }}</a></p>
    </x-prose>
</x-layouts::app>
