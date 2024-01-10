<?php

namespace App\EventListener;

use App\Event\MovieRatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class MovieRatedListener
{
    #[AsEventListener(event: MovieRatedEvent::class)]
    public function onMovieRatedEvent(MovieRatedEvent $event): void
    {
        // ...
    }
}
