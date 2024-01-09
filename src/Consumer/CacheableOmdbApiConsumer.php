<?php

namespace App\Consumer;

use App\Enum\SearchTypeEnum;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[When(env: 'prod')]
#[AsDecorator(decorates: OmdbApiConsumer::class, priority: 5)]
class CacheableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        private readonly OmdbApiConsumer $inner,
        private readonly CacheInterface $cache,
        private readonly SluggerInterface $slugger,
    ) {}

    public function fetch(SearchTypeEnum $type, string $value): array
    {
        $key = $this->slugger->slug(sprintf("%s_%s", $type->getQueryParam(), $value));

        return $this->cache->get(
            $key,
            fn() => $this->inner->fetch($type, $value)
        );
    }


}
