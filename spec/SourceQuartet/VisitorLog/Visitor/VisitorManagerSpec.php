<?php

namespace spec\SourceQuartet\VisitorLog\Visitor;

use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use SourceQuartet\VisitorLog\Visitor\VisitorRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VisitorManagerSpec extends ObjectBehavior
{
    public function let(VisitorRepository $visitorRepository,
                        Request $request,
                        Config $configRepository)
    {
        $this->beConstructedWith($visitorRepository, $request, $configRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('SourceQuartet\VisitorLog\Visitor\VisitorManager');
    }
}
