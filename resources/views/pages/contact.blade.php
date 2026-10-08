<?php

use App\Notifications\ContactNotification;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\CurrentElement;
use CraftCms\Cms\User\Elements\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    private const string SUCCESS_CALLOUT_HEADING = 'Thank you, %s!';

    private const string ERROR_CALLOUT_HEADING = 'Sorry, %s.';

    private const string SUCCESS_CALLOUT_TEXT =
        'Your message has been sent. We’ll get back to you as soon as possible.';

    private const string ERROR_CALLOUT_TEXT =
        'Unfortunately, your message couldn’t be sent. Please try again later.';


    public string $title;
    public string $body;

    #[Validate('string|required|min:3|max:255', as: 'Your name')]
    public string $name = '';

    #[Validate('string|required|email|max:255', as: 'Your E-Mail')]
    public string $email = '';

    #[Validate('required', message: 'Please enter a message that is at least 10 characters long.')]
    #[Validate('min:10', message: 'Please enter a message that is at least 10 characters long.')]
    #[Validate('max:5000', message: 'Please keep your message to a maximum of 5000 characters.')]
    public string $message = '';

    public string $state = 'empty'; // 'empty', 'success', 'error'

    public function mount(#[CurrentElement] ?Entry $entry = null): void
    {
        [$this->title, $this->body] = [$entry?->title, $entry?->body];
    }

    public function render()
    {
        return $this->view()->title($this->title);
    }

    public function send(): void
    {
        $this->normalizeValues();
        $this->validate();

        // Clear old UI messages
        $this->state = '';

        // There is only one user in Craft Solo...
        $user = User::find()->first();

        // We do not use queued notifications with retries in this demo, so we have to do error handling here.
        try {
            $user->notify(new ContactNotification($this->name, $this->email, $this->message));
        } catch (\Throwable $e) {
            report($e);
            $this->state = 'error';
            return;
        }
        $this->state = 'success';

        $this->reset('message');
        $this->resetValidation();
    }

    protected function normalizeValues(): void
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
    @if ($body)
        <x-markdown :text="$body" />
    @endif

    <flux:card size="sm" class="mt-8 w-150 space-y-4" x-data>
        @if ($state === 'success')
            <x-partials.callout
                wire:transition
                :heading="sprintf(self::SUCCESS_CALLOUT_HEADING, $name)"
                :text="self::SUCCESS_CALLOUT_TEXT"
            />
        @endif

        @if ($state === 'error')
            <x-partials.callout
                wire:transition
                variant="danger"
                :heading="sprintf(self::ERROR_CALLOUT_HEADING, $name)"
                :text="self::ERROR_CALLOUT_TEXT"
            />
        @endif

        @if ($errors->any())
            <x-partials.callout
                wire:transition
                variant="danger"
                text="Please correct the highlighted errors and try again."
            />
        @endif

        @if ($state !== 'success')
            <div wire:transition class="space-y-4">
                <flux:input label="Your name" wire:model="name" placeholder="Jane Doe" />

                <flux:input
                    label="Your E-Mail"
                    wire:model="email"
                    placeholder="jane.doe@example.com"
                />

                <flux:textarea
                    label="Your message"
                    rows="5"
                    wire:model="message"
                    placeholder="Your message..."
                />

                <div class="mt-8 flex items-center justify-between">
                    <flux:button wire:click="clear" icon="arrow-path" variant="filled">
                        Reset
                    </flux:button>
                    <flux:button wire:click="send" icon="paper-airplane" variant="primary">
                        Send message
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
                    New message
                </flux:button>
            </div>
        @endif
    </flux:card>
</div>
