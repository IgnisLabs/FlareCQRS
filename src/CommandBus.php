<?php

namespace IgnisLabs\FlareCQRS;

class CommandBus extends MessageBus {
    public function dispatch($command) {
        $this->handle($command);
    }
}
