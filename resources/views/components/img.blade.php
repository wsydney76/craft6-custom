@php
    use CraftCms\Cms\Asset\Elements\Asset;
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

@php
    if (! $image instanceof Asset && $image) {
        $image = Asset::findOne($image);
    }
@endphp

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
