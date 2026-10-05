@props([
    'blocks',
])

<div {{ $attributes }}>
    @foreach ($blocks as $block)
        <x-dynamic-component :component="'blocks.' . $block->type->handle" :block="$block" />
    @endforeach
</div>
