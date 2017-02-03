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
     * Call a closure passing the actual result as a parameter
     * @param callable $callback
     */
    public function call(callable $callback) {
        $callback($this->result);
    }

    /**
     * Get the successful result
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }
}
