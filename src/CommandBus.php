<?php

namespace IgnisLabs\FlareCQRS;

class CommandBus extends MessageBus {

    /**
     * Dispatch one or more commands to their respective handlers
     * @param array ...$commands
     */
    public function dispatch(...$commands) {
        foreach ($commands as $command) {
            $this->handle($command);
        }
    }
}
