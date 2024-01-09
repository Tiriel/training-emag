<?php

namespace App\Transformer;

use App\Entity\Genre;

class OmdbToGenreTransformer
{
    public function transform(string $data): Genre
    {
        return (new Genre())->setName($data);
    }
}
