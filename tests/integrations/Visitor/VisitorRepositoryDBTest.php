<?php
use SourceQuartet\VisitorLog\VisitorModel;
use SourceQuartet\VisitorLog\Visitor\VisitorRepository;
use Laracasts\TestDummy\Factory;

class VisitorRepositoryDBTest extends TestCaseDB
{
    use MakeModels;
    protected $visitorRepository;

    /**
     * SetUp Tests
     */
    public function setUp()
    {
        parent::setUp();
        $this->visitorRepository = app('visitor.repository');
    }

    /** @test */
    public function it_add_a_new_visitor()
    {
        $visitorData = Factory::build('SourceQuartet\VisitorLog\VisitorModel');
        $visitor = $this->visitorRepository->create($visitorData->toArray());
        $this->assertEquals($visitorData->sid, $visitor->sid);
    }
}
