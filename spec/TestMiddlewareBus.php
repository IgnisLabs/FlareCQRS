<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\MessageBus;

class TestMiddlewareBus extends MessageBus {
    public function dispatch($query) {
        return $this->handle($query);
    }
}