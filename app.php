<?php

use App\RequestWrapper;
use Symfony\Component\HttpFoundation\Response;
use App\Middleware\RateLimiting;

$middlewares = [
    RateLimiting::class
];

$request = new RequestWrapper($_SERVER);
foreach ($middlewares as $middlewareClass) {
    /** @var \App\Middleware\MiddlewareInterface $middleware */
    $middleware = new $middlewareClass();
    $response = $middleware->handle($request);
    if ($response instanceof Response) {
        $response->send();
        exit;
    }
}

$response = Response::create('Hello world!');
$response->send();