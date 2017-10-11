<?php

namespace IgnisLabs\FlareCQRS\Handler\Locator;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\Handler\Resolver\Resolver;

class MapLocator implements Locator {

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * The message to handler map
     * @var string[]
     */
    private $handlers;

    /**
     * Pool of instantiated handlers
     * @var MessageHandler[]
     */
    private $handlersPool = [];

    /**
     * CallableLocator constructor.
     * @param Resolver $resolver
     * @param array $messageHandlers
     */
    public function __construct(Resolver $resolver, array $messageHandlers = []) {
        $this->resolver = $resolver;

        foreach ($messageHandlers as $message => $handler) {
            $this->addHandler($handler, $message);
        }
    }

    /**
     * Add Handler for Message
     * @param string $handler
     * @param string $message
     * @return void
     */
    public function addHandler(string $handler, string $message) : void
    {
        $this->handlers[$message] = $handler;
    }

    /**
     * Get handler
     *
     * @param string $message
     * @return MessageHandler
     */
    public function getHandler(string $message): MessageHandler
    {
        $handlerId = $this->handlers[$message] ?? null;
        if (!$handlerId) {
            throw new \RuntimeException("No handler for [$message]");
        }

        return $this->handlersPool[$handlerId]
            ?? $this->handlersPool[$handlerId] = $this->resolver->resolve($handlerId);
    }
}
