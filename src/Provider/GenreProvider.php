<?php

namespace App\Provider;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Transformer\OmdbToGenreTransformer;

class GenreProvider
{
    public function __construct(
        private readonly GenreRepository $repository,
        private readonly OmdbToGenreTransformer $transformer,
    ) {}

    public function getGenre(string $name): Genre
    {
        return $this->repository->findOneBy(['name' => $name])
            ?? $this->transformer->transform($name);
    }

    public function getFromOmdbString(string $data): iterable
    {
        foreach (explode(', ', $data) as $name) {
            yield $this->getGenre($name);
        }
    }
}
