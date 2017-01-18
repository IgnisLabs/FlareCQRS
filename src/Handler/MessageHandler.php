<?php

namespace IgnisLabs\FlareCQRS\Handler;

interface MessageHandler {
    public function handle($message);
}
