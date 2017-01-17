<?php

namespace IgnisLabs\FlareCQRS\Handler\Resolver;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;

class CallableResolver implements Resolver {

    /**
     * The callable that will be used to resolve an instance
     * @var callable
     */
    private $callable;

    /**
     * CallableResolver constructor.
     * @param callable $callable
     */
    public function __construct(callable $callable) {
        $this->callable = $callable;
    }

    /**
     * Get a handler instance using the resolver callable
     * @param string $handler
     * @return MessageHandler
     */
    public function resolve(string $handler) : MessageHandler {
        return ($this->callable)($handler);
    }
}