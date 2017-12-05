<?php

namespace IgnisLabs\FlareCQRS\Handler\Router;

interface Router {

    /**
     * Add a route from a message to a handler
     * @param string          $messageId
     * @param string|callable $handler
     * @return void
     */
    public function add(string $messageId, $handler);

    /**
     * Resolve the route of a message and return a composing function to call the handler(s)
     * @param string $messageId
     * @return callable
     */
    public function route(string $messageId) : callable;
}
