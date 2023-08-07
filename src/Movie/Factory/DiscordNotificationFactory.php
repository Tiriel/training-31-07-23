<?php

namespace App\Movie\Factory;

use App\Movie\Notification\DiscordNotification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Notifier\Notification\Notification;

class DiscordNotificationFactory implements IterableNotificationFactoryInterface
{

    public function createNotification(string $subject): Notification
    {
        return new DiscordNotification($subject);
    }

    public static function getIndex(): string
    {
        return 'discord';
    }
}
