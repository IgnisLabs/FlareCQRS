<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;

class TestHandler {
    public function __invoke($message) {
        return $message->getFoo();
    }
}
