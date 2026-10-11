<?php

namespace App\Mixins;

use Illuminate\Support\Str;

class ArticleMixin
{
    public function getExcerpt(int $limit = 200)
    {
        return fn() => Str::limit(
            (string) $this?->contentBuilder->type('text')?->first()->text,
            $limit,
            preserveWords: true,
        );
    }
}
