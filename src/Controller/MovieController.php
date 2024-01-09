<?php

namespace App\Controller;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Enum\SearchTypeEnum;
use App\Repository\MovieRepository;
use App\Transformer\OmdbToGenreTransformer;
use App\Transformer\OmdbToMovieTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'app_movie_omdb', methods: ['GET'])]
    public function omdb(string $title, OmdbApiConsumer $consumer, OmdbToMovieTransformer $movieTransformer, OmdbToGenreTransformer $genreTransformer): Response
    {
        $data = $consumer->fetch(SearchTypeEnum::Title, $title);
        $movie = $movieTransformer->transform($data);
        foreach (explode(', ', $data['Genre']) as $name) {
            $movie->addGenre($genreTransformer->transform($name));
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
