<?php

namespace Database\Seeders;

use CraftCms\Cms\Asset\Elements\Asset;
use CraftCms\Cms\Support\Facades\Elements;
use CraftCms\Cms\Support\Facades\EntryTypes;
use CraftCms\Cms\Support\Facades\Sections;
use CraftCms\Cms\User\Elements\User;
use Database\Factories\ArticleEntryFactory;
use Illuminate\Database\Seeder;
use RuntimeException;

class CreateArticlesSeeder extends Seeder
{
    public const ARTICLE_SECTION_HANDLE = 'article';
    public const ARTICLE_TYPE_HANDLE = 'article';

    public function run(): void
    {
        $this->createEntriesFromImages($this->getImages());
    }

    public function getImages()
    {
        return Asset::find()
            ->volume('images')
            ->kind('image')
            ->folderPath('seed')
            ->width('> 1000')
            ->inRandomOrder()
            ->get();
    }

    public function createEntriesFromImages($images, ?callable $onEntryCreated = null): void
    {
        $section = Sections::getSectionByHandle(self::ARTICLE_SECTION_HANDLE);

        if (!$section) {
            throw new RuntimeException('Invalid section');
        }

        $type = EntryTypes::getEntryTypeByHandle(self::ARTICLE_TYPE_HANDLE);

        if (!$type) {
            throw new RuntimeException('Invalid entry type');
        }

        $user = User::find()->admin()->one();

        if (!$user) {
            throw new RuntimeException('No admin user found.');
        }

        $total = $images->count();
        $factory = new ArticleEntryFactory();

        foreach ($images as $i => $image) {
            $entry = $factory->makeFromImage(
                image: $image,
                sectionId: $section->id,
                typeId: $type->id,
                authorId: $user->id,
            );

            if (!Elements::saveElement($entry)) {
                throw new RuntimeException(
                    'Error saving entry: ' . print_r($entry->getErrors(), true),
                );
            }

            if ($onEntryCreated) {
                $onEntryCreated($i, $total, $entry);
            }
        }
    }
}
