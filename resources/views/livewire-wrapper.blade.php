@props([
    'livewireComponent' => null,
    'title'>null,
    'data' => [],

])
<x-layouts::app :title="$title">
    @livewire($livewireComponent, $data)
</x-layouts::app>
