<?php

namespace spec\SourceQuartet\VisitorLog\Visitor;

use Illuminate\Config\Repository as Config;
use Illuminate\Session\Store as Session;
use Illuminate\Http\Request;
use SourceQuartet\VisitorLog\Visitor\VisitorRepository;
use SourceQuartet\VisitorLog\VisitorModel;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VisitorManagerSpec extends ObjectBehavior
{
    public function let(VisitorRepository $visitorRepository,
                        Session $sessionStore,
                        Config $configRepository)
    {
        $this->beConstructedWith($visitorRepository, $sessionStore, $configRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SourceQuartet\VisitorLog\Visitor\VisitorManager');
    }

    public function it_find_visitor_by_sid(VisitorRepository $visitorRepository)
    {
        $visitor = new VisitorModel;
        $visitor_id = 1;

        $visitorRepository->find($visitor_id)->shouldBeCalled()
            ->willReturn($visitor);

        $this->find($visitor_id)->shouldReturnAnInstanceOf(VisitorModel::class);

    }

    public function it_check_if_visitor_is_online(VisitorRepository $visitorRepository)
    {
        $visitor = new VisitorModel;
        $visitor->user = 1;

        $visitorRepository->findUser($visitor->user)->shouldBeCalled()
            ->willReturn($visitor);

        $this->checkOnline($visitor->user)->shouldReturn(true);
    }

    public function it_find_visitor_when_visitor_sid_isset_on_session(VisitorRepository $visitorRepository,
                                                                        Session $sessionStore)
    {
        $visitor = new VisitorModel;
        $visitor->sid = str_random(25);
        $sessionStore->set('visitor_log_sid', $visitor->sid);

        $sessionStore->has('visitor_log_sid')->shouldBeCalled()
            ->willReturn(true);

        $sessionStore->get('visitor_log_sid')->shouldBeCalled()
            ->willReturn($visitor->sid);

        $visitorRepository->find($visitor->sid)->shouldBeCalled()
            ->willReturn($visitor);

        $this->findCurrent()->shouldReturnAnInstanceOf(VisitorModel::class);
    }

    public function it_find_visitor_when_visitor_sid_is_not_set_on_session(Session $sessionStore)
    {
        $visitor = new VisitorModel;
        $visitor->sid = str_random(25);

        $sessionStore->has('visitor_log_sid')->shouldBeCalled()
            ->willReturn(false);

        $this->findCurrent()->shouldReturn(false);
    }
}
