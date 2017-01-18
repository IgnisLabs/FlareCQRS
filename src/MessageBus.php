<?php

namespace IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Locator\Locator;

abstract class MessageBus {

    /**
     * Handler locator
     * @var Locator
     */
    private $locator;

    /**
     * MessageBus constructor.
     * @param Locator $locator
     */
    public function __construct(Locator $locator) {
        $this->locator = $locator;
    }

    /**
     * @return Locator
     */
    protected function getHandlerLocator() : Locator {
        return $this->locator;
    }
}
