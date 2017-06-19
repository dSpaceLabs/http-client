<?php

namespace Dspacelabs\Component\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Dspacelabs\Component\Http\Message\Response;
use Dspacelabs\Component\Http\Message\Stream;

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
     * @var \Psr\Http\Message\ResponseInterface;
     */
    protected $response;

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
    public function sendWithRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this->send();
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        if (null === $this->request) {
            throw new \RuntimeException('No Request to Send');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->request->getUri());
        curl_setopt($ch, CURLOPT_HEADER, true); // Include Headers with output
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // curl_exec returns output of request

        if ('post' == strtolower($this->request->getMethod())) {
            curl_setopt($ch, CURLOPT_POST, true);
        }

        $headers = array();
        foreach ($this->request->getHeaders() as $name => $values) {
            $headers[] = $name.': '.implode(', ', $values);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawResponse = curl_exec($ch);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);
        curl_close($ch);

        if (false === $rawResponse) {
            throw new \RuntimeException('Invalid Curl Request: ('.$curlErrno.') '.$curlError);
        }

        $this->response = $this->parse($rawResponse);

        return $this->response;
    }

    /**
     * Parses a Raw HTTP response and converts it into a response object
     *
     * @param string $raw
     * @return \Psr\Http\Message\Response
     */
    public function parse($raw)
    {
        $lines = explode("\n", $raw);

        // Status Line
        preg_match('/HTTP\/(\d\.\d) (\d\d\d) (.*)/', $lines[0], $statusLine);
        $response = (new Response())
            ->withProtocolVersion($statusLine[1])
            ->withStatus($statusLine[2], $statusLine[3]);
        unset($lines[0]);

        // Headers
        foreach ($lines as $i => $line) {
            if ('' == trim($line)) {
                unset($lines[$i]);
                break;
            }
            $header   = explode(': ', $line);
            $response = $response->withHeader($header[0], $header[1]);
            unset($lines[$i]);
        }

        // Body
        $stream = new Stream(fopen('php://temp', 'w+'));
        $stream->write(implode("\n", $lines));
        $stream->rewind();
        $response = $response->withBody($stream);

        return $response;
    }
}
