<?php

namespace App\Console\Commands;

use CraftCms\Cms\Asset\Elements\Asset;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\User\Elements\User;
use CraftCms\Cms\Support\Facades\Elements;
use CraftCms\Cms\Support\Facades\EntryTypes;
use CraftCms\Cms\Support\Facades\Sections;

#[Signature('seed:create-articles')]
#[Description('Creates dummy articles')]
class CreateArticles extends Command
{
    public const int NUM_ARTICLES = 1;
    public const string ARTICLE_SECTION_HANDLE = 'article';
    public const string ARTICLE_TYPE_HANDLE = 'article';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $images = Asset::find()
            ->volume('images')
            ->kind('image')
            ->folderPath('seed')
            ->width('> 1000')
            ->inRandomOrder()
            ->get();

        $section = Sections::getSectionByHandle(self::ARTICLE_SECTION_HANDLE);

        if (!$section) {
            $this->error('Invalid section');
            return self::FAILURE;
        }

        $type = EntryTypes::getEntryTypeByHandle(self::ARTICLE_TYPE_HANDLE);

        if (!$type) {
            $this->error('Invalid entry type');
            return self::FAILURE;
        }

        if (
            $this->input->isInteractive() &&
            !$this->confirm("Create {$images->count()} entries of type '{$section->name}'?")
        ) {
            return self::FAILURE;
        }

        $this->info("Creating {$images->count()} entries of type '{$section->name}'.");

        $user = User::find()->admin()->one();

        if (!$user) {
            $this->error('No admin user found.');
            return self::FAILURE;
        }

        foreach ($images as $i => $image) {
            $title = fake()->text(50);

            $this->line("[Index {$i}/{$images->count()}] {$title}");

            $entry = new Entry();

            $entry->sectionId = $section->id;
            $entry->typeId = $type->id;
            $entry->authorId = $user->id;

            // Don't let a title end with a dot
            $entry->title = rtrim($title, '.');

            $entry->postDate = fake()->dateTimeInInterval('-2 days', '-3 months');

            $entry->setFieldValue('teaser', fake()->text(40));

            $entry->setFieldValue('image', [$image->id]);

            // $entry->setFieldValue('topics', [$this->getRandomTopicId()]);

            $entry->setFieldValue('contentBuilder', [
                'sortOrder' => ['new1', 'new2', 'new3', 'new4', 'new5'],

                'entries' => [
                    'new1' => [
                        'type' => 'text',
                        'fields' => [
                            'text' => fake()->text(500),
                        ],
                    ],

                    'new2' => [
                        'type' => 'heading',
                        'fields' => [
                            'headingLevel' => 'h2',
                            'heading' => fake()->text(50),
                        ],
                    ],

                    'new3' => [
                        'type' => 'text',
                        'fields' => [
                            'text' => fake()->text(500),
                        ],
                    ],

                    'new4' => [
                        'type' => 'image',
                        'fields' => [
                            'image' => [$this->getRandomImageId()],
                            'caption' => fake()->text(50),
                        ],
                    ],

                    'new5' => [
                        'type' => 'text',
                        'fields' => [
                            'text' => fake()->text(500),
                        ],
                    ],
                ],
            ]);

            if (!Elements::saveElement($entry)) {
                $this->error('Error saving entry: ' . print_r($entry->getErrors(), true));

                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }

    private function getRandomImageId(): ?int
    {
        return Asset::find()->kind('image')->width('> 1000')->inRandomOrder()->one()?->id;
    }
}
