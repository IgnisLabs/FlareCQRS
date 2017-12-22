<?php

namespace IgnisLabs\FlareCQRS;

class CommandBus extends MessageBus {

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * Make the CommandBus dispatch events returned from handlers
     * @param EventBus $eventBus
     */
    public function dispatchesEvents(EventBus $eventBus) {
        $this->eventBus = $eventBus;
    }

    /**
     * Dispatch the command to it's handler
     * It return events the handler may have generated
     * @param mixed $command
     * @return array|\Generator|null
     */
    public function dispatch($command) {
        return $this->handleEvents($this->handle($command));
    }

    /**
     * Handle events if enabled
     * @param array|\Generator $events
     * @return array|\Generator
     */
    private function handleEvents($events = null) {
        if ($this->eventBus && is_iterable($events)) {
            foreach ($events as $event) {
                $this->eventBus->dispatch($event);
            }
        }
        return $events;
    }
}
