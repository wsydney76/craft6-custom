@php
    /** @var CraftCms\Cms\Entry\Elements\Image $block */
@endphp

@if ($image = $block->image->first())
    <figure>
        <x-img class="rounded" :image="$image" width="768" height="350" />
        @if ($block->caption)
            <figcaption>{{ $block->caption }}</figcaption>
        @endif
    </figure>
@endif
