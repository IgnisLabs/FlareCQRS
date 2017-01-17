<?php

namespace IgnisLabs\FlareCQRS\Handler\Resolver;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;

interface Resolver {
    /**
     * Get a handler instance using the resolver callable
     * @param string $handler
     * @return MessageHandler
     */
    public function resolve(string $handler) : MessageHandler;
}