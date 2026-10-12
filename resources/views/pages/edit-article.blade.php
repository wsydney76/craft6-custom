<?php

use CraftCms\Cms\Entry\Elements\Entry;
use Livewire\Component;
use CraftCms\Cms\Support\Facades\Elements;

new class extends Component {
    public int $id;
    public ?string $title = 'Title of the article';
    public ?string $slug = 'slug-of-the-article';
    public ?string $teaser = 'Teaser of the article';

    public function mount($entry): void
    {
        $this->populateProps($entry);
    }

    public function save(): void
    {
        $entry = Entry::findOne($this->id);

        $this->authorize('save', $entry);

        $entry->title = $this->title;
        $entry->slug = $this->slug;
        $entry->setFieldValue('teaser', $this->teaser);

        if (! Elements::saveElement($entry)) {
            foreach ($entry->getFirstErrors() as $attribute => $error) {
                $this->addError($attribute, $error);
            }
            Flux::toast('Article could not be saved', $entry->title, variant: 'danger');
            return;
        }

        // Repopulate the props, so that the form reflects the saved values
        // (e.g. if the slug was changed by Craft)
        $this->populateProps($entry);

        Flux::toast('Article saved successfully!', $entry->title, variant: 'success');
    }

    public function resetProps(): void
    {
        $entry = Entry::findOne($this->id);
        $this->resetValidation();
        $this->populateProps($entry);
    }

    public function regenerateSlug(): void
    {
        $this->slug = Str::slug($this->title);
    }

    protected function populateProps($entry): void
    {
        [$this->id, $this->title, $this->slug, $this->teaser] = [
            $entry->id,
            $entry->title,
            $entry->slug,
            $entry->teaser,
        ];
    }
};
?>

<flux:card>
    <form wire:submit="save" class="space-y-4">
        <flux:input label="Title" wire:model="title" />

        <flux:input label="Slug" wire:model="slug">
            <x-slot name="iconTrailing">
                <flux:button
                    variant="ghost"
                    icon="arrow-path"
                    tooltip="Regenerate Slug"
                    wire:click="regenerateSlug"
                ></flux:button>
            </x-slot>
        </flux:input>

        <flux:input label="Teaser" wire:model="teaser" />

        <div class="flex justify-between">
            <flux:button type="button" variant="filled" size="sm" wire:click="resetProps">
                Reset
            </flux:button>
            <flux:button type="submit" variant="primary" size="sm">Save</flux:button>
        </div>
    </form>
</flux:card>
