<?php

namespace spec\IgnisLabs\FlareCQRS\Handler\Resolver;

use IgnisLabs\FlareCQRS\Handler\Resolver\PSR11Resolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use spec\IgnisLabs\FlareCQRS\TestHandler;

class PSR11ResolverSpec extends ObjectBehavior
{
    function let(ContainerInterface $container, TestHandler $testHandler)
    {
        $container->get('whatevah')->willReturn($testHandler);
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PSR11Resolver::class);
    }

    function it_resolves_a_handler(TestHandler $testHandler)
    {
        $this->resolve('whatevah')->shouldBe($testHandler);
    }
}
