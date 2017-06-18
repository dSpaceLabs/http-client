<?php

namespace Dspacelabs\Component\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple HTTP Client for making requests
 */
interface Client
{
    /**
     * Sends HTTP request to server and returns a response object
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request);
}
