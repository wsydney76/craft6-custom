@php
    use CraftCms\Cms\Support\Facades\HtmlSanitizers;
    use CraftCms\Cms\Support\Facades\Markdown;
@endphp

@props([
    'text',
])

{!! Markdown::parse($text) |> HtmlSanitizers::sanitize(...) !!}
