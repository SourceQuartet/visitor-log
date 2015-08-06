<?php namespace SourceQuartet\VisitorLog\Repositories\Visitor;

use Carbon\Carbon;
use SourceQuartet\Exception\InvalidArgumentException;
use SourceQuartet\VisitorLog\Useragent;

interface Visitor
{
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
     * @param Carbon $carbon
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function clear(Carbon $carbon, $time = null);

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
     * @return null|Useragent
     */
    public function getAgentAttribute();

    /**
     * @param null $value
     * @throws InvalidArgumentException
     */
    public function setSidAttribute($value = null);

    /**
     * @return mixed|string
     */
    public function getAgentsAttribute();

    /**
     * @param null $key
     * @return null
     */
    public function is_browser($key = null);

    /**
     * @param null $key
     * @return null
     */
    public function is_robot($key = null);

    /**
     * @param null $key
     * @return null
     */
    public function is_mobile($key = null);

    /**
     * @return null
     */
    public function is_referral();

    /**
     * @return null
     */
    public function getPlatformAttribute();

    /**
     * @return null
     */
    public function getBrowserAttribute();

    /**
     * @return null
     */
    public function getVersionAttribute();

    /**
     * @return null
     */
    public function getRobotAttribute();

    /**
     * @return null
     */
    public function getMobileAttribute();

    /**
     * @return null
     */
    public function getReferrerAttribute();
}