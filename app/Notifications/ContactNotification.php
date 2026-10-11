<?php

namespace App\Notifications;

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
    public function __construct(public string $name, public string $email, public string $message)
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
            ->subject('Message from ' . $this->name)
            ->line('Name: ' . $this->name)
            ->line('Email: ' . $this->email)
            ->line(new HtmlString(nl2br(e($this->message))));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ];
    }
}
