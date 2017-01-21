<?php

namespace spec\IgnisLabs\FlareCQRS;

class TestMessage {
    public $foo;

    public function __construct($foo) {
        $this->foo = $foo;
    }

    public function getFoo() {
        return $this->foo;
    }
}