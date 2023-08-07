<?php

namespace App\Movie\Notifier\Factory;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\Notification\Notification;

class ChainNotificationFactory implements NotificationFactoryInterface
{
    /** @var IterableNotificationFactoryInterface[]  */
    private readonly iterable $factories;
    public function __construct(
        #[TaggedIterator('app.notification_factory', defaultIndexMethod: 'getIndex')] iterable $factories
    )
    {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function createNotification(string $subject, string $channel = 'email'): Notification
    {
        return $this->factories[$channel]->createNotification($subject);
    }
}
