<?php

namespace Tests\Feature\Api\Quotes;

use App\Models\ApiToken;
use App\Services\Quote\QuoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->withToken(ApiToken::firstOrFail()->token);

        Http::preventStrayRequests();
        Cache::forget(QuoteRepository::CACHE_KEY);

        Http::fake([
            'api.kanye.rest/*' => Http::sequence()
                ->push(['quote' => 'quote 1'])
                ->push(['quote' => 'quote 2'])
                ->push(['quote' => 'quote 3'])
                ->push(['quote' => 'quote 4'])
                ->push(['quote' => 'quote 5']),
        ]);
    }

    public function testGetRequestRetrievesQuotesIfNoneCached(): void
    {
        $response = $this->get('/api/v1/quotes');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                ['quote' => 'quote 1'],
                ['quote' => 'quote 2'],
                ['quote' => 'quote 3'],
                ['quote' => 'quote 4'],
                ['quote' => 'quote 5'],
            ],
        ]);

        Http::assertSentCount(5);
    }

    public function testCachedQuotesAreReturned(): void
    {
        $response1 = $this->get('/api/v1/quotes');
        $response1->assertStatus(200);
        $response1->assertJson([
            'data' => [
                ['quote' => 'quote 1'],
                ['quote' => 'quote 2'],
                ['quote' => 'quote 3'],
                ['quote' => 'quote 4'],
                ['quote' => 'quote 5'],
            ],
        ]);

        $response2 = $this->get('/api/v1/quotes');
        $response2->assertStatus(200);
        $response2->assertJson([
            'data' => [
                ['quote' => 'quote 1'],
                ['quote' => 'quote 2'],
                ['quote' => 'quote 3'],
                ['quote' => 'quote 4'],
                ['quote' => 'quote 5'],
            ],
        ]);

        Http::assertSentCount(5);
    }
}
