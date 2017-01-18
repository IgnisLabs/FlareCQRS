<?php

namespace IgnisLabs\FlareCQRS\Query;

// Synchronous promise
use IgnisLabs\FlareCQRS\Handler\MessageHandler;

class Result {
    /**
     * Handler result
     */
    private $result;

    /**
     * Result constructor.
     * @param MessageHandler $handler
     * @param $query
     */
    public function __construct(MessageHandler $handler, $query) {
        $this->result = $handler->handle($query);
    }

    /**
     * Execute the closure on successful result
     * @param callable $callback
     */
    public function then(callable $callback) {
        $callback($this->result);
    }

    /**
     * Get the successful result
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
