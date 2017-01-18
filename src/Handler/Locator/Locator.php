<?php

namespace IgnisLabs\FlareCQRS\Handler\Locator;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;

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
     * @return MessageHandler
     */
    public function getHandler(string $message) : MessageHandler;
}
