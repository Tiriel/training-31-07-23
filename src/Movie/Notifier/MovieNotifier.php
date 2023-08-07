<?php

namespace App\Movie\Notifier;

use App\Movie\Notifier\Factory\ChainNotificationFactory;
use App\Movie\Notifier\Factory\NotificationFactoryInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        private readonly ChainNotificationFactory $factory
    ) {}

    public function sendNewMovieNotification(string $title): void
    {
        $user = new class {
            public function getEmail(): string
            {
                return 'me@me.com';
            }

            public function getPreferredChannel(): string
            {
                return 'slack';
            }
        };

        $msg = sprintf("The movie %s was added in our database!", $title);
        $notification = $this->factory->createNotification($msg, $user->getPreferredChannel());

        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }
}
