<?php

namespace Dspacelabs\Component\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple HTTP Client for making requests
 */
interface ClientInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return static
     */
    public function withRequest(RequestInterface $request);

    /**
     * Sends HTTP request to server and returns a response object
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send();
}
