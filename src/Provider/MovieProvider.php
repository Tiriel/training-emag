<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Entity\User;
use App\Enum\SearchTypeEnum;
use App\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MovieProvider
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbToMovieTransformer $movieTransformer,
        private readonly GenreProvider $genreProvider,
        private readonly Security $security,
    ) {}

    public function getMovie(SearchTypeEnum $type, string $value): Movie
    {
        if (SearchTypeEnum::Title === $type
            && $movie = $this->manager->getRepository(Movie::class)->omdbSearchTitle($value)
        ) {
            $this->checkRated($movie);

            return $movie;
        }

        $data = $this->consumer->fetch($type, $value);
        $movie = $this->movieTransformer->transform($data);

        $this->checkRated($movie);

        if (($user = $this->security->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
        }

        $genres = $this->genreProvider->getFromOmdbString($data['Genre']);
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    private function checkRated($movie): void
    {
        if (!$this->security->isGranted('movie.rated', $movie)) {
            throw new AccessDeniedException();
        }
    }
}
