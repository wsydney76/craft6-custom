<?php

namespace App\Console\Commands;

use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\confirm;

#[Signature('entries:generate-missing-image-transforms')]
#[Description('Fetches all entries with a non-empty URI once to trigger missing image transforms')]
class GenerateMissingImageTransforms extends Command
{
    public function handle(): int
    {
        if (
            !confirm(
                'Fetch all entries to trigger creating missing image transforms in the background?',
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
