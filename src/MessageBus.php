<?php

namespace IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Locator\Locator;
use IgnisLabs\FlareCQRS\Handler\Router\Router;

abstract class MessageBus {

    /**
     * Handler router
     * @var Router
     */
    private $router;
    /**
     * @var Middleware[]
     */
    private $middlewares;

    /**
     * MessageBus constructor.
     * @param Router     $router
     * @param callable[] ...$middlewares
     */
    public function __construct(Router $router, callable ...$middlewares) {
        $this->router = $router;
        $this->middlewares = $middlewares;
    }

    /**
     * Replace middlewares
     * It will create another bus instance, thus, not affecting subsequent runs
     * @param callable[] $middlewares
     * @return static
     */
    public function middlewares(callable ...$middlewares) {
        return new static($this->router, ...$middlewares);
    }

    /**
     * Add a middleware to the chain
     * It will create another bus instance, thus, not affecting subsequent runs
     * @param callable $middleware
     * @return static
     */
    public function addMiddleware(callable $middleware) {
        return new static($this->router, ...array_merge($this->middlewares, [$middleware]));
    }

    /**
     * Handle the message and pass the result along
     * @param object $message
     * @return mixed
     */
    protected function handle($message) {
        return ($this->middlewareChain())($message);
    }

    private function middlewareChain() {
        $this->validateMiddlewares();

        // Our core function is the actual message handler call
        $coreFunction = $this->createCoreFunction();

        // Create our chain from the core outwards
        $middlewareChain = array_reduce(array_reverse($this->middlewares), function ($next, $middleware) {
            return $this->createNext($next, $middleware);
        }, $coreFunction);

        return $middlewareChain;
    }

    /**
     * Check if middlewares are Middleware instances
     * @throws \RuntimeException
     */
    private function validateMiddlewares() {
        foreach ($this->middlewares as $middleware) {
            if (!is_callable($middleware)) {
                throw new \RuntimeException(
                    sprintf('[%s] should be [callable]', get_class($middleware))
                );
            }
        }
    }

    /**
     * Create the middleware chain core function
     * @return callable
     */
    private function createCoreFunction() : callable {
        return function($message) {
            return $this->route($message);
        };
    }

    /**
     * Create next middleware chain link
     * @param callable $next
     * @param callable $middleware
     * @return callable
     */
    private function createNext(callable $next, callable $middleware) : callable {
        return function ($message) use ($next, $middleware) {
            return $middleware($message, $next);
        };
    }

    /**
     * Execute route for message
     * @param $message
     * @return mixed
     */
    private function route($message) {
        return $this->router->route(get_class($message))($message);
    }
}
