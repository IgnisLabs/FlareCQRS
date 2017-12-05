<?php

namespace spec\IgnisLabs\FlareCQRS\Handler\Router;

use IgnisLabs\FlareCQRS\Handler\Resolver\Resolver;
use IgnisLabs\FlareCQRS\Handler\Router\SingleHandlerRouter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SingleHandlerRouterSpec extends ObjectBehavior
{
    function let(Resolver $resolver)
    {
        $this->beConstructedWith($resolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SingleHandlerRouter::class);
    }

    function it_should_add_a_handler_id_for_a_message_and_route_to_it(Resolver $resolver)
    {
        $resolver->resolve('some.handler.id')->willReturn(function() {})->shouldBeCalled();
        $this->add('some.message.id', 'some.handler.id');
        $this->route('some.message.id')(new \stdClass);
    }

    function it_should_add_a_callable_handler_for_a_message_and_route_to_it(Resolver $resolver)
    {
        $resolver->resolve('some.handler.id')->shouldNotBeCalled();
        $this->add('some.message.id', function() {});
        $this->route('some.message.id')(new \stdClass);
    }

    function it_should_only_add_one_handler_per_message(StubHandler $handlerA, StubHandler $handlerB)
    {
        $message = new \stdClass;
        $this->add('some.message.id', $handlerA);
        $this->add('some.message.id', $handlerB);
        $this->route('some.message.id')($message);

        $handlerA->__invoke()->shouldNotHaveBeenCalled();
        $handlerB->__invoke($message)->shouldHaveBeenCalled();
    }
}
