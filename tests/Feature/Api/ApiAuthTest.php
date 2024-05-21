<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function testApiReturns401IfUnauthenticated(): void
    {
        $response = $this->get('/api/v1/quotes');

        $response->assertStatus(401);
    }

    public function testApiReturns401IfIncorrectToken(): void
    {
        $this->withToken('abc');
        $response = $this->get('/api/v1/quotes');

        $response->assertStatus(401);
    }
}
