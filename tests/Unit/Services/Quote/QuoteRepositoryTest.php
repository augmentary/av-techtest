<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Quote;

use App\Services\Quote\Quote;
use App\Services\Quote\QuoteApi;
use App\Services\Quote\QuoteRepository;
use Illuminate\Cache\Repository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QuoteRepositoryTest extends TestCase
{
    private MockObject&Repository $cache;

    private MockObject&QuoteApi $quoteApi;

    private QuoteRepository $quoteRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->createMock(Repository::class);
        $this->quoteApi = $this->createMock(QuoteApi::class);
        $this->quoteRepository = new QuoteRepository($this->cache, $this->quoteApi);
    }

    public function testFetchCached(): void
    {
        $callback = null;
        $this->cache->expects($this->exactly(1))
            ->method('remember')
            ->with(
                QuoteRepository::CACHE_KEY,
                null,
                $this->callback(function ($arg) use (&$callback) {
                    $callback = $arg;

                    return true;
                })
            )
            ->willReturn([
                'quote 0',
                'quote 1',
                'quote 2',
            ]);

        $result = $this->quoteRepository->fetchCached();

        $this->assertCount(3, $result);
        foreach ($result as $i => $quote) {
            $this->assertInstanceOf(Quote::class, $quote);
            $this->assertEquals("quote $i", $quote->quote);
        }

        $this->quoteApi->expects($this->exactly(1))
            ->method('fetch')
            ->with(5);
        $callback();
    }

    public function testRefresh(): void
    {
        $quotes = [
            'quote 0',
            'quote 1',
            'quote 2',
        ];

        $this->quoteApi->expects($this->exactly(1))
            ->method('fetch')
            ->with(5)
            ->willReturn($quotes);

        $this->cache->expects($this->exactly(1))
            ->method('set')
            ->with(
                QuoteRepository::CACHE_KEY,
                $quotes,
            );

        $result = $this->quoteRepository->refresh();

        $this->assertCount(3, $result);
        foreach ($result as $i => $quote) {
            $this->assertInstanceOf(Quote::class, $quote);
            $this->assertEquals("quote $i", $quote->quote);
        }
    }
}
