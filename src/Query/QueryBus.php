<?php

namespace IgnisLabs\Flare\Query;

use IgnisLabs\Flare\MessageBus;

class QueryBus extends MessageBus {

    public function dispatch($query) {
        return new Result($this->getHandler($query), $query);
    }
}
