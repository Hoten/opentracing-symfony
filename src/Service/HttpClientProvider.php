<?php

namespace Hoten\OpenTracingBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use OpenTracing\Tracer;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientProvider
{
    private $tracer;
    private $format;

    public function __construct(Tracer $tracer, string $format)
    {
        $this->tracer = $tracer;
        $this->format = $format;
    }

    public function get()
    {
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(Middleware::mapRequest([$this->getRequestMiddleware()]));
        $stack->push(Middleware::mapResponse([$this->getResponseMiddleware()]));
        return new Client(['handler' => $stack]);
    }

    public function getRequestMiddleware()
    {
        return function (RequestInterface $request) {
            $carrier = [];
            $this->tracer->inject($this->format, $carrier);
            return \GuzzleHttp\Psr7\modify_request($request, [
                'set_headers' => $carrier
            ]);
        };
    }

    public function getResponseMiddleware()
    {
        return function (ResponseInterface $response) {
            $this->tracer->extract($this->format, $response->getHeaders());
            return $response;
        };
    }
}
