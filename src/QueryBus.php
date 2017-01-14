<?php

namespace IgnisLabs\Qry;

class QueryBus {
    /**
     * Query to Handler map
     * @var array
     */
    private $map = [];

    /*
     * A pool of instantiated handlers
     * @var array
     */
    private $handlers;

    public function __construct(iterable $map = []) {
        foreach ($map as $query => $handler) {
            $this->addHandler($query, $handler);
        }
    }

    public function addHandler(string $query, string $handler) {
        $this->map[$query] = $handler;
    }

    public function execute($query) {
        return new ResultPromise($this->getHandler($query), $query);
    }

    public function getHandler($query): Handler {
        $queryClass = get_class($query);
        $handlerClass = $this->handlers[$query] ?? null;
        
        if (!$handlerClass) {
            throw new DomainException("No handler for [$queryClass]");
        }

        return $this->handlers[$handlerClass] ?? new $handlerClass; // replace with container loading
    }
}