<x-layouts::app :entry="$entry">
    <x-prose class>
        <x-markdown :text="$entry->body" />
    </x-prose>
    <div class="mt-8">
        <livewire:pages::search />
    </div>
</x-layouts::app>
