<?php

namespace Dspacelabs\Component\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple HTTP Client
 */
class Client implements ClientInterface
{
    /**
     * @var \Psr\Http\Message\RequestInterface;
     */
    protected $request;

    /**
     * {@inheritDoc}
     */
    public function withRequest(RequestInterface $request)
    {
        $client = clone $this;
        $client->request = $request;

        return $client;
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {
    }
}
