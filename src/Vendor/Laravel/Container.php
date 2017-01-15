<?php

namespace IgnisLabs\Flare\Vendor\Laravel;

use IgnisLabs\Flare\Support\Container as ContainerContract
use Illuminate\Container\Container as IlluminateContainer;

class Container implements ContainerContract {

    /**
     * @var IlluminateContainer
     */
    private $container;

    public function __construct(IlluminateContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve a class by it's alias in an app container
     *
     * @param string $abstract
     * @return mixed
     */
    public function resolve(string $abstract)
    {
        return $this->container->make($abstract);
    }
}
