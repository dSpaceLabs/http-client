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
        curl_close($ch);

        $response = $this->parse($rawResponse);

        return $response;
    }

    public function parse($raw)
    {
        $lines = explode("\n", $raw);

        preg_match('/(.*)\/(\d\.\d) (\d\d\d) (.*)/', $lines[0], $statusLine);
        $response = (new Response())
            ->withProtocolVersion($statusLine[2])
            ->withStatus($statusLine[3], $statusLine[4]);
        unset($lines[0]);

        foreach ($lines as $i => $line) {
            if ('' == trim($line)) {
                unset($lines[$i]);
                break;
            }
            $header = explode(': ', $line);
            $response = $response->withHeader($header[0], $header[1]);
            unset($lines[$i]);
        }

        $stream = new Stream(fopen('php://temp', 'w+'));
        $stream->write(implode("\n", $lines));
        $response = $response->withBody($stream);

        return $response;
    }
}
