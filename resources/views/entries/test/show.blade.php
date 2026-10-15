<x-layouts::app :entry="$entry">
    <x-prose>
        <p class="font-semibold">{{ $entry->name }}</p>
        <x-markdown :text="$entry->body" />
    </x-prose>
</x-layouts::app>
