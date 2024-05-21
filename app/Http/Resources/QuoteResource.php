<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Services\Quote\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Quote
 */
class QuoteResource extends JsonResource
{
    /**
     * @return array{quote: string}
     */
    public function toArray(Request $request): array
    {
        return [
            'quote' => $this->quote,
        ];
    }
}
