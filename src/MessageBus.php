<?php

namespace IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Locator\Locator;

abstract class MessageBus {

    /**
     * Handler locator
     * @var Locator
     */
    private $locator;
    /**
     * @var Middleware[]
     */
    private $middlewares;

    /**
     * MessageBus constructor.
     * @param Locator $locator
     * @param callable[] $middlewares
     */
    public function __construct(Locator $locator, callable ...$middlewares) {
        $this->locator = $locator;
        $this->middlewares = $middlewares;
    }

    /**
     * Replace middlewares
     * It will create another bus instance, thus, not affecting subsequent runs
     * @param callable[] $middlewares
     * @return static
     */
    public function middlewares(callable ...$middlewares) {
        return new static($this->locator, ...$middlewares);
    }

    /**
     * Add a middleware to the chain
     * It will create another bus instance, thus, not affecting subsequent runs
     * @param callable $middleware
     * @return static
     */
    public function addMiddleware(callable $middleware) {
        return new static($this->locator, ...array_merge($this->middlewares, [$middleware]));
    }

    /**
     * Get the message handler
     * @param object $message
     * @return callable
     */
    protected function getHandler($message) : callable {
        return $this->locator->getHandler(get_class($message));
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
            return $this->getHandler($message)($message);
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
}
