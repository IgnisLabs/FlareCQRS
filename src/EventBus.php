<?php

namespace IgnisLabs\FlareCQRS;

class EventBus extends MessageBus {

    /**
     * Dispatch one or more events to their handlers
     * @param mixed $event
     * @return void
     */
    public function dispatch($event) {
        $this->handle($event);
    }
}
