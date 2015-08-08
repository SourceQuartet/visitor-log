<?php
use Laracasts\TestDummy\Factory;

trait MakeModels
{
    /**
     * Create Visitor
     *
     * @param array $data
     * @return mixed
     */
    protected function createVisitor(array $data = [])
    {
        return Factory::create('SourceQuartet\VisitorLog\VisitorModel', $data);
    }
    /**
     * Create User
     *
     * @param array $data
     * @return mixed
     */
    protected function createUser(array $data = [])
    {
        return Factory::create('SourceQuartet\Tests\Models\User', $data);
    }
}
