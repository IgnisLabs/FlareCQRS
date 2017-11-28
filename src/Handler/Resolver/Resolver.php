<?php

namespace IgnisLabs\FlareCQRS\Handler\Resolver;

interface Resolver {
    /**
     * Get a handler instance using the resolver callable
     * @param string $handler
     * @return callable
     */
    public function resolve(string $handler) : callable;
}
