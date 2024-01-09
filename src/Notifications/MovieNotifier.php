<?php

namespace App\Notifications;

use App\Entity\Customer;
use App\Entity\Movie;
use App\Notifications\Factory\NotificationFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        /** @var NotificationFactoryInterface[] $factories */
        #[TaggedIterator(tag: 'app.notification_factory', defaultIndexMethod: 'getIndex')]
        private iterable $factories,
    ) {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function sendNotification(Customer $customer, Movie $movie): void
    {
        $msg = sprintf("The movie %s has been added to the database!", $movie->getTitle());

        $notification = $this->factories[$customer->getPreferredChannel()]->createNotification($msg);

        $this->notifier->send($notification, new Recipient($customer->getEmail()));
    }
}
