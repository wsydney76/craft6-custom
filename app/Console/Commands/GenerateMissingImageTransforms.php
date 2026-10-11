<?php

namespace App\Console\Commands;

use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('entries:generate-missing-image-transforms')]
#[Description('Fetches all entries with a non-empty URI once to trigger missing image transforms')]
class GenerateMissingImageTransforms extends Command
{
    public function handle(): int
    {
        if (
            !$this->confirm(
                'This will fetch all entries to trigger missing image transforms. Do you want to continue?',
            )
        ) {
            $this->line('Operation cancelled.');
            return self::FAILURE;
        }

        $entries = Entry::find()->uri(':notempty:')->all();

        foreach ($entries as $entry) {
            $this->line("Fetching entry: {$entry->title}");
            Http::get($entry->url);
        }

        // TODO: Fetch all pages > 1 for articles index.

        return self::SUCCESS;
    }
}
