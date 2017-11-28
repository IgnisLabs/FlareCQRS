<?php

namespace spec\IgnisLabs\FlareCQRS;

class TestMiddleware {
    public function __invoke($message, \Closure $next) {
        $message->foo = 'bar';
        return $next($message);
    }
}
