<?php

namespace IgnisLabs\FlareCQRS\Query;

use IgnisLabs\FlareCQRS\MessageBus;

class QueryBus extends MessageBus {

    public function dispatch($query) : Result {
        return new Result($this->handle($query));
    }
}
