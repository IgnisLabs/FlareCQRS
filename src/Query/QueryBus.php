<?php

namespace IgnisLabs\Flare\Query;

use IgnisLabs\Flare\MessageBus;

class QueryBus extends MessageBus {

    public function dispatch($query) {
        return new ResultPromise($this->getHandler($query), $query);
    }
}
