<?php

namespace App\Middleware;

use App\RequestWrapper;

interface MiddlewareInterface
{
    public function handle(RequestWrapper $request);
}