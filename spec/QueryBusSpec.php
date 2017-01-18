<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Locator\Locator;
use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\QueryBus;
use IgnisLabs\FlareCQRS\QueryBus\Result;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QueryBusSpec extends ObjectBehavior
{
    function let(Locator $locator, MessageHandler $handler)
    {
        $locator->getHandler(get_class(Argument::type('stdClass')))->willReturn($handler);
        $this->beConstructedWith($locator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QueryBus::class);
    }

    function it_dispatches_a_message_to_the_corresponding_handler()
    {
        $this->dispatch(Argument::type('stdClass'))->shouldBeAnInstanceOf(Result::class);
    }
}
