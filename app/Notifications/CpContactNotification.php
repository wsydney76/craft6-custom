<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Data\ContactDTO;
use CraftCms\Cms\Cp\Data\NotificationButtonData;
use CraftCms\Cms\Cp\Enums\ButtonVariant;
use CraftCms\Cms\Cp\Notifications\CpNotification;
use Illuminate\Support\Str;

class CpContactNotification extends CpNotification
{
    public function __construct(public ContactDTO $data)
    {
        $url = route('notifications');

        parent::__construct(
            sprintf(
                '**%s** set a message “%s”.',
                $data->name,
                Str::limit($data->message, 50, '...'),
            ),
        );

        $this->title('New message')
            ->byline($data->name)
            // ->image(url: $image->url, alt: 'Test only')
            ->url($url)
            ->buttons([
                new NotificationButtonData(
                    label: 'View comment',
                    url: $url,
                    variant: ButtonVariant::Primary,
                ),
            ]);
    }
}
