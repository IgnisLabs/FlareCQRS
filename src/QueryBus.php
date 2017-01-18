<?php

namespace IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\QueryBus\Result;

class QueryBus extends MessageBus {

    /**
     * Dispatch the query to it's handler
     * @param $query
     * @return Result
     */
    public function dispatch($query) : Result {
        return new Result($this->handle($query));
    }
}
