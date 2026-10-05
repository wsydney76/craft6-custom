@php
    use CraftCms\Cms\Support\Html;
@endphp

@php
    /** @var CraftCms\Cms\Entry\Elements\Heading $block */
@endphp

{!! Html::tag($block->headingLevel->value ?? 'h2', $block->heading) !!}
