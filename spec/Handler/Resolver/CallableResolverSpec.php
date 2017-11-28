<?php

namespace spec\IgnisLabs\FlareCQRS\Handler\Resolver;

use IgnisLabs\FlareCQRS\Handler\Resolver\CallableResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\IgnisLabs\FlareCQRS\TestHandler;

class CallableResolverSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function(string $handlerId) {
            return new TestHandler();
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CallableResolver::class);
    }

    function it_resolves_a_handler()
    {
        $this->resolve('whatevah')->shouldBeAnInstanceOf(TestHandler::class);
    }
}
