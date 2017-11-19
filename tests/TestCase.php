<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use lasselehtinen\Issuu\Issuu;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates a instance with a mocked response
     * @param  string $response
     * @return Issuu
     */
    public function createMockedInstance($response)
    {
        // Create a mock and queue response.
        $mock = new MockHandler([
            new Response(200, [], $response),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Create a new instance with mocked Guzzle
        $issuu = new Issuu('', '', $client);

        return $issuu;
    }
}
