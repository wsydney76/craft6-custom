@php
    use CraftCms\Cms\Asset\Elements\Asset;

    $image = Asset::find()->first();

    $transformed = imagerx()->transformImage($image, [
        'width' => 400,
        'height' => 300,
    ]);
@endphp

<h1>Blade: {{ $image->title }}</h1>

<img src="{{ $transformed->url }}" />

<hr />

{{ imagerx()->getDominantColor($image, 10, 'hex') }}

<hr />

{{ imagerx()->transformImage($image, [400, 800, 16 / 9]) |> srcset(...) }}
