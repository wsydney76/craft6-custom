@props([
    "href",
    "image" => null,
    "heading",
    "width" => 450,
    "height" => 200,
    "dominantColor" => $image?->analysis?->colors?->dominant??null,
])
<div
    @if ($dominantColor)
        style="--dominant-color: {{ $dominantColor }}"
    @endif
    {{
        $attributes->class([
            "overflow-hidden rounded-xl border border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-800",
            "hover:bg-[color-mix(in_srgb,var(--dominant-color)_18%,white)] focus-within:bg-[color-mix(in_srgb,var(--dominant-color)_18%,white)] motion-safe:transition-colors dark:hover:bg-[color-mix(in_srgb,var(--dominant-color)_22%,var(--color-zinc-700))] dark:focus-within:bg-[color-mix(in_srgb,var(--dominant-color)_22%,var(--color-zinc-700))]" => $dominantColor,
        ])
    }}
>
    <div class="overflow-hidden">
        <a href="{{ $href }}" class="group">
            <x-img
                class="transition-transform group-hover:scale-105 group-focus:scale-105"
                :image="$image"
                :width="$width"
                :height="$height"
            />
        </a>
    </div>

    <div class="mt-4 space-y-2 px-4 pb-4">
        <flux:heading size="lg">
            <a href="{{ $href }}" class="hover:underline">
                {{ $heading }}
            </a>
        </flux:heading>

        @if ($dominantColor)
            <div
                class="h-1 w-full rounded-full"
                style="background-color: {{ $dominantColor }}"
            ></div>
        @endif

        {{ $slot }}
    </div>
</div>
