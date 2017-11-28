<?php

namespace IgnisLabs\FlareCQRS\Handler\Locator;

interface Locator {

    /**
     * Add Handler for Message
     * @param string $handler
     * @param string $message
     * @return void
     */
    public function addHandler(string $handler, string $message);

    /**
     * Get handler
     *
     * @param string $message
     * @return callable
     */
    public function getHandler(string $message) : callable;
}
