<?php

namespace IgnisLabs\FlareCQRS\QueryBus;

class Result {

    /**
     * Handler result
     */
    private $result;

    /**
     * Result constructor
     * @param mixed $result
     */
    public function __construct($result) {
        $this->result = $result;
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
