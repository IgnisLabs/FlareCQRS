<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;

class TestHandler implements MessageHandler {
    public function handle($message) {
        return $message->getFoo();
    }
}