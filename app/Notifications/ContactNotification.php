<?php

namespace App\Notifications;

use App\Data\ContactDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ContactNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ContactDTO $data)
    {
        //
    }

    public function databaseType(): string
    {
        return 'contact-notification';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return new MailMessage()
            ->subject('Message from ' . $this->data->name)
            ->line('Name: ' . $this->data->name)
            ->line('Email: ' . $this->data->email)
            ->line(new HtmlString(nl2br(e($this->data->message))));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->data->name,
            'email' => $this->data->email,
            'message' => $this->data->message,
        ];
    }
}
