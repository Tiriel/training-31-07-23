<?php

namespace App\Movie\Factory;

use App\Movie\Notification\SlackNotification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Notifier\Notification\Notification;

#[AutoconfigureTag('app.notification_factory')]
class SlackNotificationFactory implements IterableNotificationFactoryInterface
{

    public function createNotification(string $subject): Notification
    {
        return new SlackNotification($subject);
    }

    public static function getIndex(): string
    {
        return 'slack';
    }
}
