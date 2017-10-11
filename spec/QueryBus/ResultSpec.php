<?php

namespace spec\IgnisLabs\FlareCQRS\QueryBus;

use IgnisLabs\FlareCQRS\QueryBus\Result;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResultSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('this is the result');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Result::class);
    }

    function it_can_defer_the_handler_result_to_a_callable()
    {
        $this->call(function ($result) {
            $this->getResult()->shouldBe($result);
        });
    }
}
