<?php

namespace spec\IgnisLabs\FlareCQRS;

use IgnisLabs\FlareCQRS\Handler\Router\Router;
use IgnisLabs\FlareCQRS\QueryBus;
use IgnisLabs\FlareCQRS\QueryBus\Result;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QueryBusSpec extends ObjectBehavior
{
    function let(Router $router, TestHandler $handler)
    {
        $router->route(get_class(new \stdClass()))->willReturn($handler);
        $this->beConstructedWith($router);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QueryBus::class);
    }

    function it_dispatches_a_message_to_the_corresponding_handler(TestHandler $handler)
    {
        $query = new \stdClass();

        $handler->__invoke($query)->shouldBeCalled();
        $this->dispatch($query)->shouldBeAnInstanceOf(Result::class);
    }
}
