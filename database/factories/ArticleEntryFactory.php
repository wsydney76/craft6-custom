<?php

namespace Database\Factories;

use CraftCms\Cms\Asset\Elements\Asset;
use CraftCms\Cms\Entry\Elements\Entry;

class ArticleEntryFactory
{
    public function makeFromImage(
        Asset $image,
        int $sectionId,
        int $typeId,
        int $authorId,
    ): Entry {
        $entry = new Entry();
        $entry->sectionId = $sectionId;
        $entry->typeId = $typeId;
        $entry->authorId = $authorId;

        // Don't let a title end with a dot
        $entry->title = rtrim(fake()->text(50), '.');
        $entry->postDate = fake()->dateTimeInInterval('-2 days', '-3 months');

        $entry->setFieldValue('teaser', fake()->text(40));
        $entry->setFieldValue('image', [$image->id]);

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

        return $entry;
    }

    private function getRandomImageId(): ?int
    {
        return Asset::find()->kind('image')->width('> 1000')->inRandomOrder()->one()?->id;
    }
}
