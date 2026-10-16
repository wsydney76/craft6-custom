<?php

namespace Database\Factories;

use CraftCms\Cms\Asset\Elements\Asset;
use CraftCms\Cms\Entry\Elements\Entry;

class ArticleEntryFactory
{
    public function makeFromImage(Asset $image, int $sectionId, int $typeId, int $authorId): Entry
    {
        $entry = new Entry();
        $entry->sectionId = $sectionId;
        $entry->typeId = $typeId;
        $entry->authorId = $authorId;

        // Don't let a title end with a dot
        $entry->title = rtrim(fake()->text(50), '.');
        $entry->postDate = fake()->dateTimeInInterval('-2 days', '-3 months');

        $entry->setFieldValue('teaser', fake()->text(40));
        $entry->setFieldValue('image', [$image->id]);

        /*
         * Prompt:
         *
         * improve creation of contentBuilder field, making it more random
            create 4 - 10 sub-entries
            make sure no 2 sub-entries of the same type follow each other
            make sure a heading is not the last entry
            make sure heading levels (h2, h3) are used in a semantically correct order (h2 before h3)
         */

        $subEntryCount = random_int(4, 10);
        $sortOrder = [];
        $entries = [];
        $previousType = null;
        $hasSeenH2 = false;

        for ($i = 1; $i <= $subEntryCount; $i++) {
            $types = ['text', 'heading', 'image'];
            $isLast = $i === $subEntryCount;

            if ($previousType !== null) {
                $types = array_values(
                    array_filter($types, static fn(string $type): bool => $type !== $previousType),
                );
            }

            if ($isLast) {
                $types = array_values(
                    array_filter($types, static fn(string $type): bool => $type !== 'heading'),
                );
            }

            $type = $types[array_rand($types)];
            $entryKey = 'new' . $i;

            $fields = $this->getContentBuilderFieldsByType($type, $hasSeenH2);
            if ($type === 'heading' && ($fields['headingLevel'] ?? null) === 'h2') {
                $hasSeenH2 = true;
            }

            $entries[$entryKey] = [
                'type' => $type,
                'fields' => $fields,
            ];
            $sortOrder[] = $entryKey;
            $previousType = $type;
        }

        $entry->setFieldValue('contentBuilder', [
            'sortOrder' => $sortOrder,
            'entries' => $entries,
        ]);

        return $entry;
    }

    private function getRandomImageId(): ?int
    {
        return Asset::find()->kind('image')->width('> 1000')->inRandomOrder()->one()?->id;
    }

    private function getContentBuilderFieldsByType(string $type, bool $hasSeenH2): array
    {
        return match ($type) {
            'heading' => [
                'headingLevel' => fake()->randomElement($hasSeenH2 ? ['h2', 'h3'] : ['h2']),
                'heading' => rtrim(fake()->sentence(random_int(3, 8)), '.'),
            ],
            'image' => [
                'image' => [$this->getRandomImageId()],
                'caption' => fake()->text(50),
            ],
            default => [
                'text' => fake()->text(random_int(250, 700)),
            ],
        };
    }
}
