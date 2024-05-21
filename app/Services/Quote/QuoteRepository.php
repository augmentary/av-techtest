<?php

declare(strict_types=1);

namespace App\Services\Quote;

use Illuminate\Cache\Repository as CacheRepository;

class QuoteRepository
{
    public const CACHE_KEY = __CLASS__.'::QUOTES';

    private const COUNT = 5;

    public function __construct(
        private readonly CacheRepository $cache,
        private readonly QuoteApi $api
    ) {
    }

    /**
     * @return list<Quote>
     */
    public function fetchCached(): array
    {
        $quotes = $this->cache->remember(
            self::CACHE_KEY,
            null,
            fn () => $this->api->fetch(self::COUNT),
        );

        return $this->mapStringsToDtos($quotes);
    }

    /**
     * @return list<Quote>
     */
    public function refresh(): array
    {
        $quotes = $this->api->fetch(self::COUNT);
        $this->cache->set(self::CACHE_KEY, $quotes);

        return $this->mapStringsToDtos($quotes);
    }

    /**
     * @param  list<string>  $strings
     * @return list<Quote>
     */
    private function mapStringsToDtos(array $strings): array
    {
        return array_map(static fn (string $quote) => new Quote($quote), $strings);

    }
}
