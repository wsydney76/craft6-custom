@props([
    'image' => null,
    'imageId' => null,
])

<div {{ $attributes->class(['px-4 lg:px-0']) }}>
    <x-img :$image :$imageId class="rounded-xl" :sizes="[768, 480]" width="1024" height="400" />
</div>
