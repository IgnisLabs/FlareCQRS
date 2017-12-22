<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\CommandBus;
use IgnisLabs\FlareCQRS\EventBus;
use IgnisLabs\FlareCQRS\Handler\Router\Router;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandBusSpec extends ObjectBehavior
{
    function let(Router $router, TestHandler $handler)
    {
        $router->route(get_class(new \stdClass()))->willReturn($handler);
        $this->beConstructedWith($router);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandBus::class);
    }

    function it_dispatches_a_command(TestHandler $handler)
    {
        $command = new \stdClass();

        $handler->__invoke($command)->shouldBeCalled();
        $this->dispatch($command);
    }

    function it_can_dispatch_events_generated_from_handler(TestHandler $handler, EventBus $eventBus)
    {
        $command = new \stdClass();
        $handler->__invoke($command)->shouldBeCalled()->willReturn(['event-1', 'event-2']);

        $this->dispatchesEvents($eventBus);
        $this->dispatch($command);

        $eventBus->dispatch('event-1')->shouldHaveBeenCalled();
        $eventBus->dispatch('event-2')->shouldHaveBeenCalled();
    }
}
