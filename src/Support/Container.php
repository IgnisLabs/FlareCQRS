<?php

namespace IgnisLabs\Flare\Support;

interface Container
{
    /**
     * Resolve a class by it's alias in an app container
     *
     * @param string $abstract
     * @return mixed
     */
    public function resolve(string $abstract);
}
