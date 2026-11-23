<?php

use App\Data\ContactDTO;
use App\Notifications\ContactNotification;
use App\Notifications\CpContactNotification;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use CraftCms\Cms\User\Elements\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

use function CraftCms\Cms\t;

new class extends Component {
    #[Validate('string|required|min:3|max:255')]
    public string $name = '';

    #[Validate('string|required|email|max:255')]
    public string $email = '';

    #[Validate('required|min:10|max:5000')]
    public string $message = '';

    public string $state = 'empty'; // 'empty', 'success', 'error'

    protected function messages(): array
    {
        $minimumLengthMessage = t('Please enter a message that is at least 10 characters long.', category: 'site');

        return [
            'message.required' => $minimumLengthMessage,
            'message.min' => $minimumLengthMessage,
            'message.max' => t('Please keep your message to a maximum of 5000 characters.', category: 'site'),
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => t('Your name', category: 'site'),
            'email' => t('Your E-Mail', category: 'site'),
        ];
    }

    public function send(): void
    {

        $this->normalizeValues();
        $this->validate();

        // Clear old UI messages
        $this->state = '';

        // There is only one user in Craft Solo...
        $user = User::find()->first();
        $data = ContactDTO::make($this->name, $this->email, $this->message);

        // We do not use queued notifications with retries in this demo, so we have to do error handling here.
        try {
            // Custom notification
            $user->notify(new ContactNotification($data));

            // Example of sending a notification to the Craft Control Panel user
            Notification::send($user, new CpContactNotification($data));
        } catch (\Throwable $e) {
            report($e);
            $this->state = 'error';
            return;
        }

        $this->state = 'success';
        $this->reset('message');
        $this->resetValidation();
    }

    public function normalizeValues(): void
    {
        $this->name = str($this->name)
            ->trim()
            ->toString();
        $this->email = str($this->email)
            ->trim()
            ->lower()
            ->toString();
        $this->message = str($this->message)
            ->trim()
            ->toString();
    }

    public function clear(): void
    {
        $this->reset(['name', 'email', 'message']);
        $this->clearValidation();
        $this->state = 'empty';
    }
};
?>

<div class="space-y-8">
    <flux:card size="sm" class="mt-8 space-y-4" x-data>
        @if ($state === 'success')
            <x-partials.callout
                wire:transition
                :heading="t('Thank you, {name}!', ['name' => $name], category: 'site')"
                :text="t('Your message has been sent. We’ll get back to you as soon as possible.', category: 'site')"
            />
        @endif

        @if ($state === 'error')
            <x-partials.callout
                wire:transition
                variant="danger"
                :heading="t('Sorry, {name}.', ['name' => $name], category: 'site')"
                :text="t('Unfortunately, your message couldn’t be sent. Please try again later.', category: 'site')"
            />
        @endif

        @if ($errors->any())
            <x-partials.callout
                wire:transition
                variant="danger"
                :text="t('Please correct the highlighted errors and try again.', category: 'site')"
            />
        @endif

        @if ($state !== 'success')
            <div wire:transition class="space-y-4">
                <flux:input
                    :label="t('Your name', category: 'site')"
                    wire:model="name"
                    :placeholder="t('Jane Doe', category: 'site')"
                />

                <flux:input
                    :label="t('Your E-Mail', category: 'site')"
                    wire:model="email"
                    :placeholder="t('jane.doe@example.com', category: 'site')"
                />

                <flux:textarea
                    :label="t('Your message', category: 'site')"
                    rows="5"
                    wire:model="message"
                    :placeholder="t('Your message...', category: 'site')"
                />

                <div class="mt-8 flex items-center justify-between">
                    <flux:button wire:click="clear" icon="arrow-path" variant="filled">
                        {{ t('Reset', category: 'site') }}
                    </flux:button>
                    <flux:button wire:click="send" icon="paper-airplane" variant="primary">
                        {{ t('Send message', category: 'site') }}
                    </flux:button>
                </div>
            </div>
        @else
            <div class="mt-8 flex items-center justify-end" wire:transition>
                <flux:button
                    wire:click="$set('state', 'empty')"
                    icon="pencil-square"
                    variant="primary"
                >
                    {{ t('New message', category: 'site') }}
                </flux:button>
            </div>
        @endif
    </flux:card>
</div>
