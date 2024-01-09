<?php

namespace App\Provider;

use App\Entity\Genre;

class GenreProvider
{
    public function getGenre(string $name): Genre
    {
        // get from database
        // if not exist, transform
    }

    public function getFromOmdbString(string $data): iterable
    {
        // explode then getGenre
    }
}
