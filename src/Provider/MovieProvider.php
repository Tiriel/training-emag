<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Enum\SearchTypeEnum;
use App\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbToMovieTransformer $movieTransformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getMovie(SearchTypeEnum $type, string $value): Movie
    {
        if (SearchTypeEnum::Title === $type
            && $movie = $this->manager->getRepository(Movie::class)->omdbSearchTitle($value)
        ) {
            return $movie;
        }

        $data = $this->consumer->fetch($type, $value);
        $movie = $this->movieTransformer->transform($data);

        $genres = $this->genreProvider->getFromOmdbString($data['Genre']);
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
