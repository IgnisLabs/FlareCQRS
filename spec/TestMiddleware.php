<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Middleware;

class TestMiddleware implements Middleware {
    public function execute($message, \Closure $next) {
        $message->foo = 'bar';
        return $next($message);
    }
}