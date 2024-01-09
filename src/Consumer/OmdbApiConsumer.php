<?php

namespace App\Consumer;

use App\Enum\SearchTypeEnum;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $omdbClient)
    {
        $this->client = $omdbClient;
    }

    public function fetch(SearchTypeEnum $type, string $value): array
    {
        $data = $this->client->request(
            'GET',
            '',
            ['query' => [
                'plot' => 'full',
                $type->getQueryParam() => $value
            ]]
        )->toArray();

        if (\array_key_exists('Error', $data)) {
            if ($data['Error'] === 'MovieNot Found!') {
                throw new NotFoundHttpException('Movie not found!');
            }

            throw new BadRequestException();
        }

        return $data;
    }
}
