<?php

namespace App\Transformer;

use App\Entity\Movie;

class OmdbToMovieTransformer
{
    public function transform(array $data): Movie
    {
        $date = $data['Released'] === 'N/A' ? '01-01-'.$data['Year'] : $data['Released'];

        return (new Movie())
            ->setTitle($data['Title'])
            ->setPlot($data['Plot'])
            ->setCountry($data['Country'])
            ->setPoster($data['Poster'])
            ->setImdbId($data['imdbID'])
            ->setRated($data['Rated'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPrice(500)
            ;
    }
}
