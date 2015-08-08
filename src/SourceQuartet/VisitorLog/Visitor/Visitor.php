<?php namespace SourceQuartet\VisitorLog\Visitor;

use Carbon\Carbon;
use SourceQuartet\Exception\InvalidArgumentException;
use SourceQuartet\VisitorLog\Useragent;

interface Visitor
{
    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']);

    /**
     * @param null $userAgent
     * @return null|string
     */
    public function setAgentToDetector($userAgent = null);
    
    /**
     * @param null $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function find($id = null);

    /**
     * @param array $attributes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function updateOrCreate(array $attributes);

    /**
     * @param $id
     * @return bool
     */
    public function checkOnline($id);

    /**
     * @return bool
     */
    public function findCurrent();

    /**
     * @param null $time
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function clear($time = null);

    /**
     * @return mixed
     */
    public function loggedIn();

    /**
     * @return mixed
     */
    public function guests();

    /**
     * @param $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function findUser($id);

    /**
     * @param $ip
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function findByIp($ip);

    /**
     * @return bool
     */
    public function isUser();
    
    /**
     * @return bool
     */
    public function isGuest();

    /**
     * Set Agent Detector
     */
    public function setAgentDetector();

    /**
     * @return bool
     */
    public function getUseragent();
}
