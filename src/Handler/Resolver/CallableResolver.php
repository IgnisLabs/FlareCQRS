<?php

namespace IgnisLabs\FlareCQRS\Handler\Resolver;

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
     * @return callable
     */
    public function resolve(string $handler) : callable {
        return ($this->callable)($handler);
    }
}
