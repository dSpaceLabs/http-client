<?php

namespace Dspacelabs\Component\Http\Client\Tests;

use PHPUnit\Framework\TestCase;
use Dspacelabs\Component\Http\Client\Client;
use Dspacelabs\Component\Http\Message\Request;
use Dspacelabs\Component\Http\Message\Uri;
use Dspacelabs\Component\Http\Message\Response;

class ClientTest extends TestCase
{
    public function testRawResponseParser()
    {
        $raw = <<<RAW
HTTP/1.1 200 OK
Date: Sun, 18 Oct 2009 08:56:53 GMT
Server: Apache/2.2.14 (Win32)
Last-Modified: Sat, 20 Nov 2004 07:16:26 GMT
ETag: "10000000565a5-2c-3e94b66c2e680"
Accept-Ranges: bytes
Content-Length: 44
Connection: close
Content-Type: text/html
X-Pad: avoid browser bug

{"hello":"world"}
RAW;
        $client = new Client();
        $response = $client->parse($raw);
        $contents = $response->getBody()->getContents();
        $this->assertSame('{"hello":"world"}', $contents);
    }
}
