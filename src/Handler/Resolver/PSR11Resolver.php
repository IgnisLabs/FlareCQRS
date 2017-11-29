<?php

namespace IgnisLabs\FlareCQRS\Handler\Resolver;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PSR11Resolver implements Resolver {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PSR11Resolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    /**
     * Get a handler instance using the resolver callable
     * @param string $handler
     * @return callable
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     */
    public function resolve(string $handler) : callable {
        return $this->container->get($handler);
    }
}
