<?php

namespace spec\IgnisLabs\FlareCQRS\Query;

use IgnisLabs\FlareCQRS\Handler\MessageHandler;
use IgnisLabs\FlareCQRS\Query\Result;
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
        $this->then(function ($result) {
            $this->getResult()->shouldBe($result);
        });
    }
}
