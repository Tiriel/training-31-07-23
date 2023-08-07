<?php

namespace App\Movie\Factory;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.notification_factory')]
interface IterableNotificationFactoryInterface extends NotificationFactoryInterface
{
    public static function getIndex(): string;
}
