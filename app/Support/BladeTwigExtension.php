<?php

namespace App\Support;

use CraftCms\Cms\Support\Template;
use Illuminate\Support\Facades\Blade;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;
use function var_export;

class BladeTwigExtension extends AbstractExtension
{
    /**
     * Register custom Twig functions.
     *
     * @return array<TwigFunction> Array of registered Twig functions
     */
    public function getFunctions(): array
    {
        // Define custom Twig functions
        // (see https://twig.symfony.com/doc/3.x/advanced.html#functions)
        return [
            new TwigFunction('t2b_livewire', function (
                string $component,
                array $data = [],
            ): Markup {
                $arrayString = var_export($data, true);
                return Template::raw(Blade::render("@livewire('$component', $arrayString)"));
            }),
        ];
    }
}
