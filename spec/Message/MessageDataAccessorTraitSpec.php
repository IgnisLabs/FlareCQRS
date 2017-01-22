<?php

namespace spec\IgnisLabs\FlareCQRS\Message;

use IgnisLabs\FlareCQRS\Message\CommandDataTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\IgnisLabs\FlareCQRS\Message\TestMessageDataAccesorTrait;

class MessageDataAccessorTraitSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(TestMessageDataAccesorTrait::class);
        $this->beConstructedWith('test', 123);
    }

    function it_has_a_data_accessor()
    {
        $this->get('foo')->shouldBe('test');
        $this->get('bar')->shouldBe(123);
    }

    function it_can_have_data_be_accessed_as_object_properties()
    {
        $this->get('foo')->shouldBe($this->foo);
        $this->get('bar')->shouldBe($this->bar);
    }
}
