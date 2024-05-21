<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Quote;

use App\Services\PendingRequestManager;
use App\Services\Quote\QuoteApi;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use PHPUnit\Framework\TestCase;

class QuoteApiTest extends TestCase
{
    public function testFetch(): void
    {
        $responses = [];
        for ($i = 0; $i < 3; $i++) {
            $responses[] = $response = $this->createMock(Response::class);
            $response->expects($this->exactly(1))
                ->method('json')
                ->with('quote')
                ->willReturnCallback(fn () => "quote $i");
        }

        $api = $this->createMock(PendingRequest::class);
        $api->expects($this->exactly(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(...$responses);

        $requestManager = $this->createMock(PendingRequestManager::class);
        $requestManager->expects($this->exactly(1))
            ->method('driver')
            ->with('quotes')
            ->willReturn($api);

        $quoteApi = new QuoteApi($requestManager);
        $result = $quoteApi->fetch(3);

        $this->assertEquals([
            'quote 0',
            'quote 1',
            'quote 2',
        ], $result);
    }
}
