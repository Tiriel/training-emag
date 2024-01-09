<?php

namespace App\Provider;

use App\Entity\Movie;
use App\Enum\SearchTypeEnum;

class MovieProvider
{
    public function getMovie(SearchTypeEnum $type, string $value): Movie
    {
        // search inside the database
        // if movie exists, return it
        // if not, fetch it from omdbapi
        // build movie object from data
        // save movie in database
        // return movie
    }
}
