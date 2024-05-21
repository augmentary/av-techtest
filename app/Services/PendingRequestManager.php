<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Manager;

class PendingRequestManager extends Manager
{
    public function __construct(Container $container, private Factory $factory)
    {
        parent::__construct($container);
    }

    public function createQuotesDriver(): PendingRequest
    {
        return $this->factory->createPendingRequest()->withOptions([
            'base_uri' => env('QUOTES_API_BASE_URI'),
        ]);
    }

    public function getDefaultDriver(): string
    {
        return 'quotes';
    }
}
