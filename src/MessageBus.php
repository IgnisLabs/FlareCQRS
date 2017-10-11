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
     * @var Middleware[]
     */
    private $middlewares;

    /**
     * MessageBus constructor.
     * @param Locator $locator
     * @param Middleware[] $middlewares
     */
    public function __construct(Locator $locator, Middleware ...$middlewares) {
        $this->locator = $locator;
        $this->middlewares = $middlewares;
    }

    /**
     * Replace middlewares
     * It will create another bus instance, thus, not affecting subsequent runs
     * @param Middleware[] $middlewares
     * @return static
     */
    public function middlewares(Middleware ...$middlewares) {
        return new static($this->locator, ...$middlewares);
    }

    /**
     * Add a middleware to the chain
     * It will create another bus instance, thus, not affecting subsequent runs
     * @param Middleware $middleware
     * @return static
     */
    public function addMiddleware(Middleware $middleware) {
        return new static($this->locator, ...array_merge($this->middlewares, [$middleware]));
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
            if (!$middleware instanceof Middleware) {
                throw new \RuntimeException(
                    sprintf('[%s] should be a [%s] instance', get_class($middleware), Middleware::class)
                );
            }
        }
    }

    /**
     * Create the middleware chain core function
     * @return \Closure
     */
    private function createCoreFunction() {
        return function($message) {
            return $this->getHandler($message)->handle($message);
        };
    }

    /**
     * Create next middleware chain link
     * @param $next
     * @param $middleware
     * @return \Closure
     */
    private function createNext($next, $middleware) {
        return function ($message) use ($next, $middleware) {
            return $middleware->execute($message, $next);
        };
    }
}
