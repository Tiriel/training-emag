<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 9999)]
final class MaintenanceListener
{
    public function __construct(
        private readonly Environment $twig,
        #[Autowire(param: 'app.maintenance')]
        private readonly bool $isMaintenance,
    ) {}

    public function __invoke(RequestEvent $event): void
    {
        if ($this->isMaintenance) {
            $response = new Response();
            if (HttpKernelInterface::MAIN_REQUEST === $event->getRequestType()) {
                $response->setContent($this->twig->render('maintenance.html.twig'));
            }

            $event->setResponse($response);
        }
    }
}
