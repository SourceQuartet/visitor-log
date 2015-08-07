<?php namespace SourceQuartet\VisitorLog\Contracts\Visitor;

use Carbon\Carbon;
use SourceQuartet\VisitorLog\Useragent;
use SourceQuartet\VisitorLog\Visitor;

interface VisitorContract
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @return static
     */
    public function updateOrCreate(array $attributes);

    /**
     * @param null $time
     * @param Carbon $carbon
     * @return mixed
     */
    public function clear($time = null, Carbon $carbon);

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
     */
    public function findUser($id);

    /**
     * @param $ip
     * @return mixed
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
     * @return null|Useragent
     */
    public function getAgentAttribute();

    /**
     * @return mixed|string
     */
    public function getAgentsAttribute();

    /**
     * @param $value
     */
    public function setSidAttribute($value);

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