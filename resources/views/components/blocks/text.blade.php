@php
    /** @var CraftCms\Cms\Entry\Elements\Text $block */
@endphp

<div>
    <x-markdown :text="$block->text" />
</div>
