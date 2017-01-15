<?php

namespace IgnisLabs\Flare\Query;

// Synchronous promise
class Result {
    /**
     * Handler result
     */
    private $result;

    /**
     * Result constructor.
     * @param Handler $handler
     * @param $query
     */
    public function __construct(Handler $handler, $query) {
        $this->result = $handler->handle($query);
    }

    /**
     * Execute the closure on successful result
     * @param callable $callback
     */
    public function then(callable $callback) {
        $callback($this->success);
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
