@php
    use Illuminate\Pagination\Paginator;
    /** @var Paginator $paginator */
@endphp

@if ($paginator->hasPages())
    <div class="flex justify-between">
        <div>
            @if (! $paginator->onFirstPage())
                <flux:button
                    variant="filled"
                    wire:click="previousPage()"
                    icon="chevron-double-left"
                >
                    {{ \CraftCms\Cms\t('Previous', category: 'site') }}
                </flux:button>
            @endif
        </div>
        <div>
            @if (! $paginator->onLastPage())
                <flux:button
                    variant="filled"
                    wire:click="nextPage()"
                    icon:trailing="chevron-double-right"
                >
                    {{ \CraftCms\Cms\t('Next', category: 'site') }}
                </flux:button>
            @endif
        </div>
    </div>
@endif
