<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
class GetSongController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    #[IsGranted('ROLE_MODERATOR')]
    #[Route('/song', name: 'app_song_get')]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('main/index.html.twig', [
            'controller_name' => 'GetSongController',
        ]));
    }
}
