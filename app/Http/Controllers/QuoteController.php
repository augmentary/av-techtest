<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Services\Quote\QuoteRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

readonly class QuoteController
{
    public function __construct(private QuoteRepository $quoteRepository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return QuoteResource::collection($this->quoteRepository->fetchCached());
    }

    public function store(): AnonymousResourceCollection
    {
        return QuoteResource::collection($this->quoteRepository->refresh());
    }
}
