<?php

namespace IgnisLabs\FlareCQRS\Handler\Router;

use IgnisLabs\FlareCQRS\Handler\Resolver\Resolver;

class MultiHandlerRouter implements Router {

    /**
     * @var callable[]
     */
    private $routes = [];

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * MultiHandlerRouter constructor.
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver) {
        $this->resolver = $resolver;
    }

    /**
     * Add a route from a message to a handler
     * @param string          $messageId
     * @param string|callable $handler
     * @return void
     */
    public function add(string $messageId, $handler) {
        if (!array_key_exists($messageId, $this->routes)) {
            $this->routes[$messageId] = [];
        }
        $this->routes[$messageId][] = $handler;
    }

    /**
     * Resolve the route of a message and get the handlers
     * @param string $messageId
     * @return callable
     */
    public function route(string $messageId) : callable {
        return function ($message) use ($messageId) {
            foreach ($this->routes[$messageId] as $handler) {
                if (!is_callable($handler)) {
                    $handler = $this->resolver->resolve($handler);
                }
                $handler($message);
            }
        };
    }
}
