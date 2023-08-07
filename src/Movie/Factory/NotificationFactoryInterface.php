<?php

namespace App\Movie\Factory;

use Symfony\Component\Notifier\Notification\Notification;

interface NotificationFactoryInterface
{
    public function createNotification(string $subject): Notification;
}
