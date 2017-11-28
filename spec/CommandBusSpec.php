<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\CommandBus;
use IgnisLabs\FlareCQRS\Handler\Locator\Locator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandBusSpec extends ObjectBehavior
{
    function let(Locator $locator, TestHandler $handler)
    {
        $locator->getHandler(get_class(new \stdClass()))->willReturn($handler);
        $this->beConstructedWith($locator);
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

    function it_can_dispatch_multiple_commands(TestHandler $handler)
    {
        $commands = [new \stdClass(), new \stdClass()];

        $handler->__invoke($commands[0])->shouldBeCalled();
        $handler->__invoke($commands[1])->shouldBeCalled();
        $this->dispatch(...$commands);
    }
}
