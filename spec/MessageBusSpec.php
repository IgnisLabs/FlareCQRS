<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\Handler\Router\Router;
use IgnisLabs\FlareCQRS\MessageBus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\IgnisLabs\FlareCQRS\Handler\Router\StubHandler;

class MessageBusSpec extends ObjectBehavior
{
    function let(Router $router)
    {
        $message = new TestMessage('foo');
        $handler = new TestHandler();
        $router->route(get_class($message))->willReturn($handler);

        $this->beAnInstanceOf(TestMiddlewareBus::class);
        $this->beConstructedWith($router, new TestMiddleware);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MessageBus::class);
    }

    function it_pipes_messages_through_middlewares()
    {
        $message = new TestMessage('foo');
        $this->dispatch($message)->shouldBe('bar');
    }

    function it_returns_a_new_instance_when_adding_middlewares()
    {
        $this->addMiddleware(new TestMiddleware)->shouldNotBe($this);
    }

    function it_returns_a_new_instance_when_replacing_middlewares()
    {
        $this->middlewares(new TestMiddleware)->shouldNotBe($this);
    }
}
