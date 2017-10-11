<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\CommandBus;
use IgnisLabs\FlareCQRS\Handler\Locator\Locator;
use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandBusSpec extends ObjectBehavior
{
    function let(Locator $locator, MessageHandler $handler)
    {
        $locator->getHandler(get_class(new \stdClass()))->willReturn($handler);
        $this->beConstructedWith($locator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandBus::class);
    }

    function it_dispatches_a_command(MessageHandler $handler)
    {
        $command = new \stdClass();

        $handler->handle($command)->shouldBeCalled();
        $this->dispatch($command);
    }

    function it_can_dispatch_multiple_commands(MessageHandler $handler)
    {
        $commands = [new \stdClass(), new \stdClass()];

        $handler->handle($commands[0])->shouldBeCalled();
        $handler->handle($commands[1])->shouldBeCalled();
        $this->dispatch(...$commands);
    }
}
