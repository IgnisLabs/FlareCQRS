<?php

namespace spec\IgnisLabs\FlareCQRS\Handler\Resolver;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\Handler\Resolver\CallableResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallableResolverSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function(string $handlerId) {
            return new class implements MessageHandler {
                public function execute($message) {}
            };
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CallableResolver::class);
    }

    function it_resolves_a_handler()
    {
        $this->resolve('whatevah')->shouldBeAnInstanceOf(MessageHandler::class);
    }
}
