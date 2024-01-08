<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
class GetSongController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    #[Route('/song', name: 'app_song_get')]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('book/index.html.twig', [
            'controller_name' => 'GetSongController',
        ]));
    }
}
