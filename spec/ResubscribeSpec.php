<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResubscribeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Resubscribe');
    }

    function it_check_cookies_and_return_false_if_we_did_not_explicit_set_cookies()
    {
        $this->checkCookies()->shouldReturn(false);
    }

    function it_check_cookies_and_return_true_after_we_set_cookies()
    {
        $this->setCookies();
        $this->checkCookies()->shouldReturn(true);
    }
    
    function it_should_display_when_cookie_is_set()
    {
        $this->setCookies();
        $this->canDisplay()->shouldReturn(true);
    }
}
