<?php

namespace IgnisLabs\FlareCQRS\Query;

use IgnisLabs\FlareCQRS\MessageBus;

class QueryBus extends MessageBus {

    public function dispatch($query) : Result {
        $handler = $this->getHandlerLocator()->getHandler(get_class($query));
        return new Result($handler->handle($query));
    }
}
