<?php

namespace Dspacelabs\Component\Http\Client\Tests;

use PHPUnit\Framework\TestCase;
use Dspacelabs\Component\Http\Client\Client;

class ClientTest extends TestCase
{
    public function testGeneric()
    {
        $request = \Mockery::mock('Psr\Http\Message\RequestInterface');

        $response = (new Client())->withRequest($request)->send();
    }
}
