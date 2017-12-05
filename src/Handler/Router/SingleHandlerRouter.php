<?php

namespace IgnisLabs\FlareCQRS\Handler\Router;

use IgnisLabs\FlareCQRS\Handler\Resolver\Resolver;

class SingleHandlerRouter implements Router {

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * SingleHandlerRouter constructor.
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
        $this->routes[$messageId] = $handler;
    }

    /**
     * Resolve the route of a message executing the handler(s)
     * @param string $messageId
     * @return callable
     */
    public function route(string $messageId) : callable {
        return function ($message) use ($messageId) {
            if ($handler = $this->routes[$messageId]) {
                if (!is_callable($handler)) {
                    $handler = $this->resolver->resolve($handler);
                }
                $handler($message);
            }
        };
    }
}
