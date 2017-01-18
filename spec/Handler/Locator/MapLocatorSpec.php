<?php

namespace spec\IgnisLabs\FlareCQRS\Handler\Locator;

use IgnisLabs\FlareCQRS\Handler\Locator\MapLocator;
use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\Handler\Resolver\Resolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MapLocatorSpec extends ObjectBehavior
{
    function let(Resolver $resolver, MessageHandler $handler)
    {
        $resolver->resolve('FooHandler')->willReturn($handler);
        $resolver->resolve('BarHandler')->willReturn($handler);

        $this->beConstructedWith($resolver, ['FooMessage' => 'FooHandler']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MapLocator::class);
    }

    function it_can_locate_a_handler_for_a_message(MessageHandler $handler)
    {
        $this->getHandler('FooMessage')->shouldBe($handler);
    }

    function it_fails_if_no_handler_is_located()
    {
        $this->shouldThrow(\RuntimeException::class)->duringGetHandler('BarMessage');
    }

    function it_can_be_provided_with_more_handlers(MessageHandler $handler)
    {
        $this->shouldThrow(\RuntimeException::class)->duringGetHandler('BarMessage');
        $this->addHandler('BarHandler', 'BarMessage');
        $this->getHandler('BarMessage')->shouldBe($handler);
    }

    function it_returns_always_the_same_handler_instance()
    {
        $this->getHandler('FooMessage')->shouldBe($this->getHandler('FooMessage'));
    }
}
