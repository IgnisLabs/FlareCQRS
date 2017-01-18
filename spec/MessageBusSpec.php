<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Locator\Locator;
use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\MessageBus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageBusSpec extends ObjectBehavior
{
    function let(Locator $locator)
    {
        $message = new TestMessage();
        $handler = new TestHandler();
        $locator->getHandler(get_class($message))->willReturn($handler);

        $this->beAnInstanceOf(TestMiddlewareBus::class);
        $this->beConstructedWith($locator, [new TestMiddleware]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MessageBus::class);
    }

    function it_pipes_messages_through_middlewares()
    {
        $message = new TestMessage();
        $this->dispatch($message)->shouldBe('bar');
    }

    function it_returns_a_new_instance_when_adding_middlewares()
    {
        $this->addMiddleware(new TestMiddleware)->shouldNotBe($this);
    }

    function it_returns_a_new_instance_when_replacing_middlewares()
    {
        $this->middlewares([new TestMiddleware])->shouldNotBe($this);
    }
}
