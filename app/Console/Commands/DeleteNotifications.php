<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

#[Signature('notifications:delete-all {--force : Delete all notifications without confirmation}')]
#[Description('Deletes all notifications from the database')]
class DeleteNotifications extends Command
{
    public function handle(): int
    {
        $count = DatabaseNotification::query()->count();

        if ($count === 0) {
            $this->info('No notifications found.');

            return self::SUCCESS;
        }

        if ($this->input->isInteractive() && ! $this->option('force')) {
            if (! $this->confirm("Delete {$count} notifications from the database?")) {
                $this->info('Aborted.');

                return self::FAILURE;
            }
        }

        DatabaseNotification::query()->delete();

        $this->info("Deleted {$count} notifications.");

        return self::SUCCESS;
    }
}

