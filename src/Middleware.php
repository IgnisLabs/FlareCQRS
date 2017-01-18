<?php

namespace IgnisLabs\FlareCQRS;

interface Middleware {
    public function execute($message, \Closure $next);
}