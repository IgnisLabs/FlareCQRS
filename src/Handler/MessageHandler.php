<?php

namespace IgnisLabs\FlareCQRS\Handler;

interface MessageHandler {
    public function execute($message);
}
