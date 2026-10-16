<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Database\Seeders\CreateArticlesSeeder;
use RuntimeException;

#[Signature('seed:create-articles')]
#[Description('Creates dummy articles')]
class CreateArticles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seeder = app(CreateArticlesSeeder::class);
        $images = $seeder->getImages();

        if (
            $this->input->isInteractive() &&
            !$this->confirm("Create {$images->count()} entries of type 'article'?")
        ) {
            return self::FAILURE;
        }

        $this->info("Creating {$images->count()} entries of type 'article'.");

        try {
            $seeder->createEntriesFromImages(
                images: $images,
                onEntryCreated: function (int $index, int $total, $entry): void {
                    $this->line("[Index {$index}/{$total}] {$entry->title}");
                },
            );
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
