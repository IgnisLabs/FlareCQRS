<?php

namespace spec\IgnisLabs\FlareCQRS;

class TestMessage {
    public $foo = 'foo';

    public function getFoo() {
        return $this->foo;
    }
}