<?php

use spacecatninja\imagerx\variables\ImagerVariable;
use CraftCms\Cms\Support\Facades\Plugins;

if (!function_exists('imagerx')) {
    function imagerx(): ImagerVariable
    {
        static $imager = null;

        if (!$imager) {
            $imager = new ImagerVariable();
        }

        return $imager;
    }
}

if (!function_exists('srcset')) {
    function srcset(?array $images, string $descriptor = 'w'): string
    {
        return Plugins::getPlugin('imager-x')->imagerx->srcset($images, $descriptor);
    }
}
