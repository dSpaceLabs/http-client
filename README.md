dspacelabs/http-client
======================

Simple HTTP Client used for PHP and making web requests.

## Installation

```bash
composer require dspacelabs/http-client
```

## Examples

### Making a Web Request

```php
use Dspacelabs\Component\Http\Client\Client;

$client = new Client();

// @var \Psr\Http\Message\Request $request
// @var \Psr\Http\Message\Response $response
$response = $client->sendWithRequest($request);
```
