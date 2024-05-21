<?php

namespace Tests\Feature\Api\Quotes;

use App\Models\ApiToken;
use App\Services\Quote\QuoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PostTest extends TestCase
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
                ->push(['quote' => 'quote 5'])
                ->push(['quote' => 'quote 6'])
                ->push(['quote' => 'quote 7'])
                ->push(['quote' => 'quote 8'])
                ->push(['quote' => 'quote 9'])
                ->push(['quote' => 'quote 10']),
        ]);
    }

    public function testPostRequestRetrievesQuotesAndRefreshesCache(): void
    {
        //prime cache so we can make sure old values are overwritten
        $response1 = $this->post('/api/v1/quotes');
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

        //1-5 should now be in cache
        $response2 = $this->post('/api/v1/quotes');
        $response2->assertStatus(200);
        $response2->assertJson([
            'data' => [
                ['quote' => 'quote 6'],
                ['quote' => 'quote 7'],
                ['quote' => 'quote 8'],
                ['quote' => 'quote 9'],
                ['quote' => 'quote 10'],
            ],
        ]);

        //ensure new values were cached
        $response3 = $this->get('/api/v1/quotes');
        $response3->assertStatus(200);
        $response3->assertJson([
            'data' => [
                ['quote' => 'quote 6'],
                ['quote' => 'quote 7'],
                ['quote' => 'quote 8'],
                ['quote' => 'quote 9'],
                ['quote' => 'quote 10'],
            ],
        ]);

        Http::assertSentCount(10);
    }
}
