@php
    use CraftCms\Cms\Support\Html;
@endphp

@props([
    'image',
    'transform' => null,
    'width' => null,
    'height' => null,
    'format' => 'webp',
    'sizes' => [],
])

@if ($image)
    @php
        if (! $transform) {
            $transform = ['width' => (int) $width, 'height' => (int) $height, 'format' => $format];
        }
        $tag = $image->getImg($transform, $sizes);
    @endphp

    @if ($attributes->isEmpty())
        {!! $tag !!}
    @else
        {!! Html::modifyTagAttributes($tag, $attributes->toArray()) !!}
    @endif
@endif
