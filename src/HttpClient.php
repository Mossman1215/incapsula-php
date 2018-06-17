<?php

namespace Incapsula;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class HttpClient extends Client
{
    public function __construct(array $config = [])
    {
        $handler = HandlerStack::create(new CurlHandler());
        $handler->push($this->getRetryMiddleware());

        parent::__construct(array_merge($config, ['handler' => $handler]));
    }

    private function getRetryMiddleware()
    {
        return Middleware::retry(
            function ($retries, $request, $response, $exception) {
                if ($retries > 10) {
                    return false;
                }
                if ($exception instanceof ConnectException) {
                    return true;
                }
                if ($response && in_array($response->getStatusCode(), [500, 502, 503, 504, 429], true)) {
                    return true;
                }

                return false;
            }
        );
    }
}
