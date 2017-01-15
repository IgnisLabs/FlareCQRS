<?php

namespace IgnisLabs\Flare;

interface MessageHandler {
    public function execute($message);
}
