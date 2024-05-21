<?php

declare(strict_types=1);

namespace App\Services\Quote;

readonly class Quote
{
    public function __construct(
        public string $quote,
    ) {
    }
}
