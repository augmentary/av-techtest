<?php

declare(strict_types=1);

namespace App\Services\Quote;

use App\Services\PendingRequestManager;
use Illuminate\Http\Client\PendingRequest;

class QuoteApi
{
    public function __construct(
        private readonly PendingRequestManager $apiManager
    ) {
    }

    /**
     * @return list<string>
     */
    public function fetch(int $count = 1): array
    {
        $result = [];

        /** @var PendingRequest $api */
        $api = $this->apiManager->driver('quotes');
        for ($i = 0; $i < $count; $i++) {
            $quote = $api->get('')->json('quote');
            assert(is_string($quote));
            $result[] = $quote;
        }

        return $result;
    }
}
