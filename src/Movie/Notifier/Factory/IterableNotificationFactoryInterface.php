<?php

namespace App\Movie\Notifier\Factory;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.notification_factory')]
interface IterableNotificationFactoryInterface extends NotificationFactoryInterface
{
    public static function getIndex(): string;
}
