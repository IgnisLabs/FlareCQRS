<?php

namespace spec\IgnisLabs\FlareCQRS\Message;

use IgnisLabs\FlareCQRS\Message\DataAccessorTrait;

class TestMessageDataAccesorTrait {
    use DataAccessorTrait;

    public function __construct(string $foo, int $bar) {
        $this->setData(compact('foo', 'bar'));
    }
}
