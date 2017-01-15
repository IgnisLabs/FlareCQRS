<?php

namespace IgnisLabs\Flare;

use IgnisLabs\Flare\Support\Container;

abstract class MessageBus {

    /**
     * @var Container
     */
    private $container;

    /**
     * Message to Handler map
     * @var array
     */
    private $handlers = [];

    /*
     * A pool of instantiated handlers map to their messages
     * @var MessageHandler[]
     */
    private $handlerPool = [];

    /**
     * MessageBus constructor.
     * @param Container $container
     * @param array $map
     */
    public function __construct(Container $container, array $map = []) {
        $this->container = $container;

        foreach ($map as $query => $handler) {
            $this->addHandler($query, $handler);
        }
    }

    /**
     * Add a Handler for a Message by their classname
     * @param string $message
     * @param string|MessageHandler $handler
     */
    public function addHandler(string $message, $handler) {
        $this->handlers[$message] = $handler;
    }

    /**
     * Get a Message's corresponding Handler instance
     * @param $message
     * @return MessageHandler
     * @throws \RuntimeException
     */
    public function getHandler($message) : MessageHandler {
        // Get the message's corresponding handler classname
        $messageClass = get_class($message);
        $handlerClass = $this->handlers[$message] ?? null;
        if (!$handlerClass) {
            throw new \RuntimeException("No handler for [$messageClass]");
        }

        // Get a handler instance, either cached or new
        $handler = $this->handlerPool[$handlerClass] ?? $this->container->resolve($handlerClass);
        if (!$handler instanceof MessageHandler) {
            throw new \RuntimeException('Handler should be an instance of [' . MessageHandler::class . ']');
        }

        return $handler;
    }
}
