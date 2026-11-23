@php
    use CraftCms\Cms\Support\Facades\HtmlSanitizers;
    use CraftCms\Cms\Support\Facades\Markdown;
@endphp

@props([
    'text',
    'flavor' => 'gfm',
])

@if ($text)
    {!! Markdown::parse($text, $flavor) |> HtmlSanitizers::sanitize(...) !!}
@endif
