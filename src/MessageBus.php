<?php

namespace IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Locator\Locator;
use IgnisLabs\FlareCQRS\Handler\MessageHandler;

abstract class MessageBus {

    /**
     * Handler locator
     * @var Locator
     */
    private $locator;

    /**
     * MessageBus constructor.
     * @param Locator $locator
     */
    public function __construct(Locator $locator) {
        $this->locator = $locator;
    }

    /**
     * Get the message handler
     * @param object $message
     * @return MessageHandler
     */
    protected function getHandler($message) : MessageHandler {
        return $this->locator->getHandler(get_class($message));
    }

    /**
     * Handle the message and pass the result along
     * @param object $message
     * @return mixed
     */
    protected function handle($message) {
        return $this->getHandler($message)->handle($message);
    }
}
