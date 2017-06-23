dspacelabs/http-client
======================

The main goal is to provide a very simple Client library that I can use for
other projects and is something that is easy to extend. This library must use
PSR-7 compliant objects.

## Basic Examples

```php
$response = $client->sendWithRequest($request);
```

This takes the `request` and will return a `response` object to be further
processed. The `response` is something that can be used by other libraries. As
an example, a client class could look like:

```php
// ...
public function getBabies()
{
    // Create a Request object

    $response = $client->sendWithRequest($request);

    // Process the Response object

    return $babies;
}
// ...
```

This should give me the flexibility to create very specific clients quickly, but
also gives me the ability to just use the "plumbing"

One issue is when dealing with APIs. Since each is different, the Request
objects will need to be generated within the specific client.

## Types of Clients

This library can have a few different "clients" such as

* HttpClient
* SoapClient
* OauthClient

Which are things I come into contact with a lot.

## Interfaces

```php
interface ClientInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendWithRequest(\Psr\Http\Message\RequestInterface $request);
}
```

```php
interface HttpClientInterface extends ClientInterface
{
}
```

```php
interface OauthClientInterface extends HttpClientInterface
{
}
```

```php
interface SoapClientInterface
{
}
```
