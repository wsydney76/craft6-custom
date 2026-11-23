<x-layouts::app :title="\CraftCms\Cms\t('404 - Page Not Found', category: 'site')">
    <x-prose>
        <p>{{ \CraftCms\Cms\t('Sorry, the page you are looking for could not be found.', category: 'site') }}</p>
        <p><a href="{{ url('/') }}">{{ \CraftCms\Cms\t('Return to Home', category: 'site') }}</a></p>
    </x-prose>
</x-layouts::app>
