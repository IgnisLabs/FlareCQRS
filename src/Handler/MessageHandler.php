<?php

namespace IgnisLabs\FlareCQRS\Handler;

interface MessageHandler {
    /**
     * Handle a message
     * @param object $message
     * @return mixed
     */
    public function handle($message);
}
