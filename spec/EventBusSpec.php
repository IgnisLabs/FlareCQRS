<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\EventBus;
use IgnisLabs\FlareCQRS\Handler\Router\Router;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventBusSpec extends ObjectBehavior
{
    function let(Router $router, TestHandler $handler)
    {
        $router->route(get_class(new \stdClass()))->willReturn($handler);
        $this->beConstructedWith($router);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventBus::class);
    }

    function it_dispatches_an_event(TestHandler $handler)
    {
        $command = new \stdClass();

        $handler->__invoke($command)->shouldBeCalled();
        $this->dispatch($command);
    }
}
