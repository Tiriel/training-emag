<?php

namespace App\Consumer;

use App\Enum\SearchTypeEnum;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[When(env: 'dev')]
#[When(env: 'prod')]
#[AsDecorator(decorates: OmdbApiConsumer::class, priority: 10)]
class LoggableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        private readonly OmdbApiConsumer $inner,
        private readonly LoggerInterface $logger,
    ) {}

    public function fetch(SearchTypeEnum $type, string $value): array
    {
        $this->logger->log('info', sprintf("Search for movie '%s' by '%s'", $value, $type->getQueryParam()));

        return $this->inner->fetch($type, $value);
    }
}
