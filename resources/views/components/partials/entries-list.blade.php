@props([
    'heading' => null,
    'entries',
])

@if ($entries->isNotEmpty())
    @if ($heading)
        <flux:heading size="xl">{{ $heading }}</flux:heading>
    @endif

    <ul>
        @foreach ($entries as $entry)
            <li>{{ $entry->link }}</li>
        @endforeach
    </ul>
@endif
